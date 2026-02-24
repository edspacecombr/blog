'use client';

import { useState, useEffect } from 'react';
import AdminLayout from '@/components/admin/layouts/AdminLayout';
import { toast } from 'react-toastify';

interface Author {
  id: number;
  name: string;
  email: string;
  avatar_url?: string;
  bio?: string;
  post_count: number;
}

export default function AuthorsPage() {
  const [authors, setAuthors] = useState<Author[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    bio: '',
    avatar_url: '',
  });

  useEffect(() => {
    fetchAuthors();
  }, []);

  const fetchAuthors = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/authors', {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (response.ok) {
        const data = await response.json();
        setAuthors(data.data);
      }
    } catch (error) {
      toast.error('Erro ao carregar autores');
    } finally {
      setIsLoading(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/authors', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        toast.success('Autor criado!');
        setFormData({ name: '', email: '', bio: '', avatar_url: '' });
        setShowForm(false);
        fetchAuthors();
      }
    } catch (error) {
      toast.error('Erro ao criar autor');
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Deletar autor?')) return;
    try {
      const token = localStorage.getItem('auth_token');
      await fetch(`/api/v1/authors/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
      });
      toast.success('Autor deletado!');
      fetchAuthors();
    } catch (error) {
      toast.error('Erro ao deletar');
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <h1 className="text-3xl font-bold">👤 Autores</h1>
          <button
            onClick={() => setShowForm(!showForm)}
            className="px-6 py-2 bg-blue-600 text-white rounded-lg"
          >
            {showForm ? '✕ Fechar' : '➕ Novo Autor'}
          </button>
        </div>

        {showForm && (
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-bold mb-4">Novo Autor</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <input
                type="text"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                placeholder="Nome do autor"
                required
              />
              <input
                type="email"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                placeholder="Email"
                required
              />
              <textarea
                value={formData.bio}
                onChange={(e) => setFormData({ ...formData, bio: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                placeholder="Bio (opcional)"
                rows={3}
              />
              <input
                type="url"
                value={formData.avatar_url}
                onChange={(e) => setFormData({ ...formData, avatar_url: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                placeholder="URL do avatar"
              />
              <button
                type="submit"
                className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg"
              >
                ✅ Criar Autor
              </button>
            </form>
          </div>
        )}

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {isLoading
            ? Array(6)
                .fill(0)
                .map((_, i) => (
                  <div key={i} className="bg-gray-200 rounded-lg h-48 animate-pulse"></div>
                ))
            : authors.map((author) => (
                <div key={author.id} className="bg-white rounded-lg shadow-md p-6">
                  {author.avatar_url && (
                    <img
                      src={author.avatar_url}
                      alt={author.name}
                      className="w-20 h-20 rounded-full mb-4 object-cover"
                    />
                  )}
                  <h3 className="font-bold text-lg">{author.name}</h3>
                  <p className="text-sm text-gray-600">{author.email}</p>
                  {author.bio && <p className="text-sm text-gray-500 mt-2 line-clamp-2">{author.bio}</p>}
                  <p className="text-xs text-gray-400 mt-4">📝 {author.post_count} posts</p>
                  <div className="flex gap-2 mt-4">
                    <button className="px-3 py-1 bg-blue-600 text-white rounded text-xs">
                      Editar
                    </button>
                    <button
                      onClick={() => handleDelete(author.id)}
                      className="px-3 py-1 bg-red-600 text-white rounded text-xs"
                    >
                      Deletar
                    </button>
                  </div>
                </div>
              ))}
        </div>
      </div>
    </AdminLayout>
  );
}
