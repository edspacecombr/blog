'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { toast } from 'react-toastify';

interface Post {
  id: number;
  title: string;
  slug: string;
  status: 'draft' | 'published' | 'scheduled';
  language: string;
  created_at: string;
  author?: {
    name: string;
  };
}

interface PostTableProps {
  filters?: {
    status?: string;
    language?: string;
    search?: string;
  };
}

export default function PostTable({ filters }: PostTableProps) {
  const [posts, setPosts] = useState<Post[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [total, setTotal] = useState(0);
  const [perPage] = useState(10);

  useEffect(() => {
    const fetchPosts = async () => {
      setIsLoading(true);
      try {
        const token = localStorage.getItem('auth_token');
        const params = new URLSearchParams({
          page: page.toString(),
          per_page: perPage.toString(),
          ...(filters?.status && { status: filters.status }),
          ...(filters?.language && { language: filters.language }),
          ...(filters?.search && { search: filters.search }),
        });

        const response = await fetch(`/api/v1/posts?${params}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.ok) {
          const data = await response.json();
          setPosts(data.data);
          setTotal(data.meta?.total || 0);
        }
      } catch (error) {
        toast.error('Erro ao carregar posts');
      } finally {
        setIsLoading(false);
      }
    };

    fetchPosts();
  }, [page, filters, perPage]);

  const handleDelete = async (id: number) => {
    if (!confirm('Tem certeza que deseja deletar este post?')) return;

    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch(`/api/v1/posts/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      if (response.ok) {
        toast.success('Post deletado com sucesso');
        setPosts(posts.filter((p) => p.id !== id));
      } else {
        toast.error('Erro ao deletar post');
      }
    } catch (error) {
      toast.error('Erro ao deletar post');
    }
  };

  const getStatusBadge = (status: string) => {
    const badgeClasses = {
      draft: 'bg-yellow-100 text-yellow-800',
      published: 'bg-green-100 text-green-800',
      scheduled: 'bg-blue-100 text-blue-800',
    };
    return badgeClasses[status as keyof typeof badgeClasses] || '';
  };

  const getStatusLabel = (status: string) => {
    const labels = {
      draft: '✏️ Rascunho',
      published: '📖 Publicado',
      scheduled: '⏱️ Agendado',
    };
    return labels[status as keyof typeof labels] || status;
  };

  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden">
      {isLoading ? (
        <div className="p-6 text-center">
          <p className="text-gray-500">Carregando posts...</p>
        </div>
      ) : posts.length === 0 ? (
        <div className="p-6 text-center">
          <p className="text-gray-500 mb-4">Nenhum post encontrado</p>
          <Link
            href="/admin/posts/new"
            className="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            Criar Primeiro Post
          </Link>
        </div>
      ) : (
        <>
          <table className="w-full">
            <thead className="bg-gray-50 border-b">
              <tr>
                <th className="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                  Título
                </th>
                <th className="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                  Autor
                </th>
                <th className="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                  Status
                </th>
                <th className="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                  Idioma
                </th>
                <th className="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                  Data
                </th>
                <th className="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                  Ações
                </th>
              </tr>
            </thead>
            <tbody>
              {posts.map((post) => (
                <tr key={post.id} className="border-b hover:bg-gray-50">
                  <td className="px-6 py-4">
                    <p className="font-medium text-gray-900">{post.title}</p>
                    <p className="text-xs text-gray-500">{post.slug}</p>
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-600">
                    {post.author?.name || 'N/A'}
                  </td>
                  <td className="px-6 py-4">
                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${getStatusBadge(post.status)}`}>
                      {getStatusLabel(post.status)}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-600">
                    {post.language.toUpperCase()}
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-600">
                    {new Date(post.created_at).toLocaleDateString('pt-BR')}
                  </td>
                  <td className="px-6 py-4 flex gap-2">
                    <Link
                      href={`/admin/posts/${post.id}/edit`}
                      className="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs"
                    >
                      Editar
                    </Link>
                    <button
                      onClick={() => handleDelete(post.id)}
                      className="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs"
                    >
                      Deletar
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {/* Pagination */}
          <div className="px-6 py-4 bg-gray-50 flex items-center justify-between">
            <p className="text-sm text-gray-600">
              Mostrando {Math.min(page * perPage, total)} de {total} posts
            </p>
            <div className="flex gap-2">
              <button
                onClick={() => setPage(Math.max(1, page - 1))}
                disabled={page === 1}
                className="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 disabled:opacity-50"
              >
                ← Anterior
              </button>
              <button
                onClick={() => setPage(page + 1)}
                disabled={page * perPage >= total}
                className="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 disabled:opacity-50"
              >
                Próximo →
              </button>
            </div>
          </div>
        </>
      )}
    </div>
  );
}
