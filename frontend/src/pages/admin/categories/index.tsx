'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import AdminLayout from '@/components/admin/layouts/AdminLayout';
import { toast } from 'react-toastify';

interface Category {
  id: number;
  name: string;
  slug: string;
  language: string;
  post_count: number;
}

export default function CategoriesPage() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [formData, setFormData] = useState({ name: '', slug: '', language: 'pt' });

  useEffect(() => {
    fetchCategories();
  }, []);

  const fetchCategories = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/categories', {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (response.ok) {
        const data = await response.json();
        setCategories(data.data);
      }
    } catch (error) {
      toast.error('Erro ao carregar categorias');
    } finally {
      setIsLoading(false);
    }
  };

  const handleCreate = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/categories', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        toast.success('Categoria criada!');
        setFormData({ name: '', slug: '', language: 'pt' });
        fetchCategories();
      }
    } catch (error) {
      toast.error('Erro ao criar categoria');
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Deletar categoria?')) return;
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch(`/api/v1/categories/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
      });
      if (response.ok) {
        toast.success('Categoria deletada!');
        fetchCategories();
      }
    } catch (error) {
      toast.error('Erro ao deletar');
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <h1 className="text-3xl font-bold text-gray-900">🏷️ Categorias</h1>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div className="lg:col-span-2">
            {isLoading ? (
              <div className="bg-white p-6 rounded-lg text-center text-gray-500">Carregando...</div>
            ) : (
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <table className="w-full">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Nome</th>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Idioma</th>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Posts</th>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    {categories.map((cat) => (
                      <tr key={cat.id} className="border-b hover:bg-gray-50">
                        <td className="px-6 py-4">
                          <p className="font-medium">{cat.name}</p>
                          <p className="text-sm text-gray-500">{cat.slug}</p>
                        </td>
                        <td className="px-6 py-4 text-sm">{cat.language.toUpperCase()}</td>
                        <td className="px-6 py-4 text-sm">{cat.post_count || 0}</td>
                        <td className="px-6 py-4 flex gap-2">
                          <button className="px-3 py-1 bg-blue-600 text-white rounded text-xs">
                            Editar
                          </button>
                          <button
                            onClick={() => handleDelete(cat.id)}
                            className="px-3 py-1 bg-red-600 text-white rounded text-xs"
                          >
                            Deletar
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>

          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-bold mb-4">➕ Nova Categoria</h2>
            <form onSubmit={handleCreate} className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-1">Nome *</label>
                <input
                  type="text"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  placeholder="Ex: Tecnologia"
                  required
                />
              </div>

              <div>
                <label className="block text-sm font-medium mb-1">Slug</label>
                <input
                  type="text"
                  value={formData.slug}
                  onChange={(e) => setFormData({ ...formData, slug: e.target.value })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  placeholder="tecnologia"
                />
              </div>

              <div>
                <label className="block text-sm font-medium mb-1">Idioma</label>
                <select
                  value={formData.language}
                  onChange={(e) => setFormData({ ...formData, language: e.target.value })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                >
                  <option value="pt">🇧🇷 Português</option>
                  <option value="en">🇺🇸 English</option>
                  <option value="es">🇪🇸 Español</option>
                </select>
              </div>

              <button
                type="submit"
                className="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
              >
                ✅ Criar
              </button>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
