'use client';

import { useEffect, useState } from 'react';
import { useParams } from 'next/navigation';
import AdminLayout from '@/components/admin/layouts/AdminLayout';
import GrapesJSEditor from '@/components/admin/GrapesJSEditor';
import MediaPicker from '@/components/admin/MediaPicker';
import { usePostForm } from '@/hooks/admin/usePostForm';

interface PostFormData {
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  category_id: number;
  author_id: number;
  language: string;
  status: 'draft' | 'published' | 'scheduled';
  featured_image_url: string;
  scheduled_at: string;
  seo_title: string;
  seo_description: string;
}

export default function PostFormPage() {
  const params = useParams();
  const postId = params?.id ? parseInt(params.id as string) : undefined;
  
  const { post, isLoading, errors, savePost, fetchPost } = usePostForm(postId);
  
  const [formData, setFormData] = useState<PostFormData>({
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    category_id: 0,
    author_id: 0,
    language: 'pt',
    status: 'draft',
    featured_image_url: '',
    scheduled_at: '',
    seo_title: '',
    seo_description: '',
  });

  const [editorContent, setEditorContent] = useState('');
  const [showMediaPicker, setShowMediaPicker] = useState(false);
  const [categories, setCategories] = useState<any[]>([]);
  const [authors, setAuthors] = useState<any[]>([]);

  useEffect(() => {
    if (postId) {
      fetchPost();
    }
    loadCategoriesAndAuthors();
  }, [postId]);

  useEffect(() => {
    if (post) {
      setFormData({
        title: post.title,
        slug: post.slug || '',
        excerpt: post.excerpt || '',
        content: post.content || '',
        category_id: post.category_id || 0,
        author_id: post.author_id || 0,
        language: post.language,
        status: post.status,
        featured_image_url: post.featured_image_url || '',
        scheduled_at: post.scheduled_at || '',
        seo_title: post.seo_title || '',
        seo_description: post.seo_description || '',
      });
      setEditorContent(post.content || '');
    }
  }, [post]);

  const loadCategoriesAndAuthors = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      
      const [catRes, authRes] = await Promise.all([
        fetch('/api/v1/categories', { headers: { Authorization: `Bearer ${token}` } }),
        fetch('/api/v1/authors', { headers: { Authorization: `Bearer ${token}` } }),
      ]);

      if (catRes.ok) {
        const catData = await catRes.json();
        setCategories(catData.data);
      }

      if (authRes.ok) {
        const authData = await authRes.json();
        setAuthors(authData.data);
      }
    } catch (error) {
      console.error('Erro ao carregar dados:', error);
    }
  };

  const generateSlug = (title: string) => {
    return title
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');
  };

  const handleTitleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const title = e.target.value;
    setFormData({
      ...formData,
      title,
      slug: generateSlug(title),
    });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    const dataToSubmit = {
      ...formData,
      content: editorContent,
    };

    const success = await savePost(dataToSubmit);
    if (success && !postId) {
      // Redirecionará automaticamente
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        {/* Header */}
        <div>
          <h1 className="text-3xl font-bold text-gray-900">
            {postId ? '✏️ Editar Post' : '➕ Novo Post'}
          </h1>
          <p className="text-gray-500 mt-1">
            {postId ? 'Atualize os detalhes do seu post' : 'Crie um novo artigo'}
          </p>
        </div>

        {/* Form */}
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Basic Info */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-bold mb-4">📝 Informações Básicas</h2>
            
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Título *
                </label>
                <input
                  type="text"
                  value={formData.title}
                  onChange={handleTitleChange}
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                    errors.title ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                  }`}
                  placeholder="Título do post"
                />
                {errors.title && <p className="text-red-500 text-sm mt-1">{errors.title}</p>}
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Slug
                  </label>
                  <input
                    type="text"
                    value={formData.slug}
                    onChange={(e) => setFormData({ ...formData, slug: e.target.value })}
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="url-friendly-slug"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Idioma *
                  </label>
                  <select
                    value={formData.language}
                    onChange={(e) => setFormData({ ...formData, language: e.target.value })}
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                  >
                    <option value="pt">🇧🇷 Português</option>
                    <option value="en">🇺🇸 English</option>
                    <option value="es">🇪🇸 Español</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Resumo *
                </label>
                <textarea
                  value={formData.excerpt}
                  onChange={(e) => setFormData({ ...formData, excerpt: e.target.value })}
                  className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                    errors.excerpt ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                  }`}
                  rows={3}
                  placeholder="Resumo do post"
                />
                {errors.excerpt && <p className="text-red-500 text-sm mt-1">{errors.excerpt}</p>}
              </div>
            </div>
          </div>

          {/* Metadata */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {/* Category & Author */}
            <div className="bg-white rounded-lg shadow-md p-6">
              <h2 className="text-xl font-bold mb-4">🏷️ Organização</h2>
              
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Categoria *
                  </label>
                  <select
                    value={formData.category_id}
                    onChange={(e) =>
                      setFormData({ ...formData, category_id: parseInt(e.target.value) })
                    }
                    className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                      errors.category_id ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                    }`}
                  >
                    <option value="0">Selecione uma categoria</option>
                    {categories.map((cat) => (
                      <option key={cat.id} value={cat.id}>
                        {cat.name}
                      </option>
                    ))}
                  </select>
                  {errors.category_id && (
                    <p className="text-red-500 text-sm mt-1">{errors.category_id}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Autor *
                  </label>
                  <select
                    value={formData.author_id}
                    onChange={(e) => setFormData({ ...formData, author_id: parseInt(e.target.value) })}
                    className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                      errors.author_id ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                    }`}
                  >
                    <option value="0">Selecione um autor</option>
                    {authors.map((auth) => (
                      <option key={auth.id} value={auth.id}>
                        {auth.name}
                      </option>
                    ))}
                  </select>
                  {errors.author_id && (
                    <p className="text-red-500 text-sm mt-1">{errors.author_id}</p>
                  )}
                </div>
              </div>
            </div>

            {/* Status & Publishing */}
            <div className="bg-white rounded-lg shadow-md p-6">
              <h2 className="text-xl font-bold mb-4">📤 Publicação</h2>
              
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Status
                  </label>
                  <select
                    value={formData.status}
                    onChange={(e) =>
                      setFormData({ ...formData, status: e.target.value as any })
                    }
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                  >
                    <option value="draft">✏️ Rascunho</option>
                    <option value="published">📖 Publicado</option>
                    <option value="scheduled">⏱️ Agendado</option>
                  </select>
                </div>

                {formData.status === 'scheduled' && (
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                      Data de Publicação
                    </label>
                    <input
                      type="datetime-local"
                      value={formData.scheduled_at}
                      onChange={(e) => setFormData({ ...formData, scheduled_at: e.target.value })}
                      className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    />
                  </div>
                )}
              </div>
            </div>
          </div>

          {/* Featured Image */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-bold mb-4">🖼️ Imagem em Destaque</h2>
            
            <div className="flex gap-4">
              {formData.featured_image_url && (
                <img
                  src={formData.featured_image_url}
                  alt="Featured"
                  className="w-32 h-32 object-cover rounded-lg"
                />
              )}
              <div className="flex-1">
                <input
                  type="url"
                  value={formData.featured_image_url}
                  onChange={(e) => setFormData({ ...formData, featured_image_url: e.target.value })}
                  placeholder="URL da imagem"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 mb-2"
                />
                <button
                  type="button"
                  onClick={() => setShowMediaPicker(true)}
                  className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                >
                  🖼️ Escolher Imagem
                </button>
              </div>
            </div>
          </div>

          {/* Editor */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-bold mb-4">✍️ Conteúdo *</h2>
            
            <GrapesJSEditor
              initialContent={editorContent}
              onChange={setEditorContent}
            />
            
            {errors.content && <p className="text-red-500 text-sm mt-2">{errors.content}</p>}
          </div>

          {/* SEO */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-bold mb-4">🔍 SEO</h2>
            
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Título SEO
                </label>
                <input
                  type="text"
                  value={formData.seo_title}
                  onChange={(e) => setFormData({ ...formData, seo_title: e.target.value })}
                  maxLength={60}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                  placeholder="Título para mecanismos de busca"
                />
                <p className="text-xs text-gray-500 mt-1">
                  {formData.seo_title.length}/60
                </p>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Descrição SEO
                </label>
                <textarea
                  value={formData.seo_description}
                  onChange={(e) => setFormData({ ...formData, seo_description: e.target.value })}
                  maxLength={160}
                  rows={3}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                  placeholder="Descrição para mecanismos de busca"
                />
                <p className="text-xs text-gray-500 mt-1">
                  {formData.seo_description.length}/160
                </p>
              </div>
            </div>
          </div>

          {/* Submit */}
          <div className="flex justify-end gap-4">
            <button
              type="button"
              onClick={() => window.history.back()}
              className="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
            >
              Cancelar
            </button>
            <button
              type="submit"
              disabled={isLoading}
              className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
            >
              {isLoading
                ? '⏳ Salvando...'
                : postId
                ? '✅ Atualizar'
                : '✅ Criar'}
            </button>
          </div>
        </form>

        {/* Media Picker Modal */}
        {showMediaPicker && (
          <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div className="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
              <div className="p-6">
                <div className="flex justify-between items-center mb-4">
                  <h3 className="text-xl font-bold">Selecionar Imagem</h3>
                  <button
                    onClick={() => setShowMediaPicker(false)}
                    className="text-gray-500 hover:text-gray-700 text-2xl"
                  >
                    ✕
                  </button>
                </div>
                
                <MediaPicker
                  onSelect={(url) => {
                    setFormData({ ...formData, featured_image_url: url });
                    setShowMediaPicker(false);
                  }}
                />
              </div>
            </div>
          </div>
        )}
      </div>
    </AdminLayout>
  );
}
