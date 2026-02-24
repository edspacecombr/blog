'use client';

import { useEffect, useState } from 'react';
import AdminLayout from '@/components/admin/layouts/AdminLayout';

interface DashboardStats {
  total_posts: number;
  total_pages: number;
  total_categories: number;
  total_authors: number;
  published_posts: number;
  draft_posts: number;
}

export default function AdminDashboard() {
  const [stats, setStats] = useState<DashboardStats>({
    total_posts: 0,
    total_pages: 0,
    total_categories: 0,
    total_authors: 0,
    published_posts: 0,
    draft_posts: 0,
  });
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/v1/dashboard/stats', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (response.ok) {
          const data = await response.json();
          setStats(data.data);
        }
      } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
      } finally {
        setIsLoading(false);
      }
    };

    fetchStats();
  }, []);

  return (
    <AdminLayout>
      <div className="space-y-6">
        {/* Header */}
        <div className="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg">
          <h1 className="text-4xl font-bold mb-2">📊 Dashboard</h1>
          <p className="text-blue-100">Bem-vindo ao painel administrativo do seu blog</p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {/* Posts */}
          <div className="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-500 text-sm">Total de Posts</p>
                <p className="text-3xl font-bold text-gray-900 mt-2">
                  {isLoading ? '...' : stats.total_posts}
                </p>
              </div>
              <div className="text-4xl">📝</div>
            </div>
            <p className="text-xs text-gray-400 mt-4">
              📖 Publicados: {stats.published_posts} | ✏️ Rascunhos: {stats.draft_posts}
            </p>
          </div>

          {/* Pages */}
          <div className="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-500 text-sm">Páginas Estáticas</p>
                <p className="text-3xl font-bold text-gray-900 mt-2">
                  {isLoading ? '...' : stats.total_pages}
                </p>
              </div>
              <div className="text-4xl">📄</div>
            </div>
            <p className="text-xs text-gray-400 mt-4">
              Páginas principais do site
            </p>
          </div>

          {/* Categories */}
          <div className="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-600">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-500 text-sm">Categorias</p>
                <p className="text-3xl font-bold text-gray-900 mt-2">
                  {isLoading ? '...' : stats.total_categories}
                </p>
              </div>
              <div className="text-4xl">🏷️</div>
            </div>
            <p className="text-xs text-gray-400 mt-4">
              Tópicos para organizar conteúdo
            </p>
          </div>

          {/* Authors */}
          <div className="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-500 text-sm">Autores</p>
                <p className="text-3xl font-bold text-gray-900 mt-2">
                  {isLoading ? '...' : stats.total_authors}
                </p>
              </div>
              <div className="text-4xl">👤</div>
            </div>
            <p className="text-xs text-gray-400 mt-4">
              Perfis de autores cadastrados
            </p>
          </div>
        </div>

        {/* Quick Actions */}
        <div className="bg-white rounded-lg shadow-md p-6">
          <h2 className="text-2xl font-bold mb-4">🚀 Ações Rápidas</h2>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a
              href="/admin/posts/new"
              className="flex items-center gap-3 p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
            >
              <span className="text-2xl">✍️</span>
              <div>
                <p className="font-semibold text-gray-900">Novo Post</p>
                <p className="text-xs text-gray-500">Criar um artigo</p>
              </div>
            </a>

            <a
              href="/admin/media"
              className="flex items-center gap-3 p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors"
            >
              <span className="text-2xl">🖼️</span>
              <div>
                <p className="font-semibold text-gray-900">Mídia</p>
                <p className="text-xs text-gray-500">Gerenciar imagens</p>
              </div>
            </a>

            <a
              href="/admin/categories"
              className="flex items-center gap-3 p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors"
            >
              <span className="text-2xl">🏷️</span>
              <div>
                <p className="font-semibold text-gray-900">Categorias</p>
                <p className="text-xs text-gray-500">Organizar conteúdo</p>
              </div>
            </a>

            <a
              href="/admin/settings"
              className="flex items-center gap-3 p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors"
            >
              <span className="text-2xl">⚙️</span>
              <div>
                <p className="font-semibold text-gray-900">Configurações</p>
                <p className="text-xs text-gray-500">Ajustes do site</p>
              </div>
            </a>
          </div>
        </div>

        {/* Tips */}
        <div className="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-6">
          <h3 className="font-semibold text-blue-900 mb-2">💡 Dica</h3>
          <p className="text-blue-800 text-sm">
            Comece criando categorias e autores antes de publicar seus primeiros artigos.
            Isso ajudará a organizar melhor o conteúdo do seu blog.
          </p>
        </div>
      </div>
    </AdminLayout>
  );
}
