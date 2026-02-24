import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { toast } from 'react-toastify';
import { Post, postSchema } from '@/lib/validators';

export function usePostForm(postId?: number) {
  const router = useRouter();
  const [isLoading, setIsLoading] = useState(false);
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [post, setPost] = useState<Post | null>(null);

  const validatePost = (data: any) => {
    try {
      setErrors({});
      postSchema.parse(data);
      return true;
    } catch (error: any) {
      const fieldErrors: Record<string, string> = {};
      if (error.errors) {
        error.errors.forEach((err: any) => {
          fieldErrors[err.path.join('.')] = err.message;
        });
      }
      setErrors(fieldErrors);
      return false;
    }
  };

  const savePost = async (data: any) => {
    if (!validatePost(data)) return false;

    setIsLoading(true);
    try {
      const token = localStorage.getItem('auth_token');
      const method = postId ? 'PATCH' : 'POST';
      const url = postId ? `/api/v1/posts/${postId}` : '/api/v1/posts';

      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(data),
      });

      if (!response.ok) {
        throw new Error('Erro ao salvar post');
      }

      const result = await response.json();
      toast.success(postId ? 'Post atualizado com sucesso!' : 'Post criado com sucesso!');
      
      if (!postId) {
        router.push(`/admin/posts/${result.data.id}/edit`);
      }
      
      return true;
    } catch (error: any) {
      toast.error(error.message || 'Erro ao salvar post');
      return false;
    } finally {
      setIsLoading(false);
    }
  };

  const fetchPost = async () => {
    if (!postId) return;

    setIsLoading(true);
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch(`/api/v1/posts/${postId}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      if (response.ok) {
        const data = await response.json();
        setPost(data.data);
      }
    } catch (error) {
      toast.error('Erro ao carregar post');
    } finally {
      setIsLoading(false);
    }
  };

  return {
    post,
    isLoading,
    errors,
    savePost,
    fetchPost,
  };
}
