'use client';

import { ReactNode, useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { ToastContainer } from 'react-toastify';

interface AdminLayoutProps {
  children: ReactNode;
}

export default function AdminLayout({ children }: AdminLayoutProps) {
  const router = useRouter();
  const [sidebarOpen, setSidebarOpen] = useState(true);

  const menuItems = [
    { href: '/admin', label: '📊 Dashboard', icon: '📊' },
    { href: '/admin/setup', label: '⚙️ Setup Wizard', icon: '⚙️' },
    { href: '/admin/posts', label: '📝 Posts', icon: '📝' },
    { href: '/admin/pages', label: '📄 Páginas', icon: '📄' },
    { href: '/admin/categories', label: '🏷️ Categorias', icon: '🏷️' },
    { href: '/admin/authors', label: '👤 Autores', icon: '👤' },
    { href: '/admin/menus', label: '📋 Menus', icon: '📋' },
    { href: '/admin/media', label: '🖼️ Mídia', icon: '🖼️' },
    { href: '/admin/settings', label: '⚙️ Configurações', icon: '⚙️' },
    { href: '/admin/languages', label: '🌍 Idiomas', icon: '🌍' },
  ];

  const handleLogout = () => {
    localStorage.removeItem('auth_token');
    router.push('/admin/login');
  };

  return (
    <div className="flex h-screen bg-gray-100">
      {/* Sidebar */}
      <aside
        className={`${
          sidebarOpen ? 'w-64' : 'w-20'
        } bg-gray-800 text-white transition-all duration-300 overflow-y-auto`}
      >
        <div className="p-4 border-b border-gray-700">
          <h1 className={`font-bold text-xl ${!sidebarOpen && 'text-center'}`}>
            {sidebarOpen ? '🚀 Blog Admin' : '📝'}
          </h1>
        </div>

        <nav className="p-4 space-y-2">
          {menuItems.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors"
            >
              <span className="text-xl">{item.icon}</span>
              {sidebarOpen && <span className="text-sm">{item.label.split(' ')[1]}</span>}
            </Link>
          ))}
        </nav>

        <div className="absolute bottom-4 left-0 right-0 px-4">
          <button
            onClick={handleLogout}
            className="w-full px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors text-sm"
          >
            {sidebarOpen ? '🚪 Sair' : '🚪'}
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
          <div className="flex items-center gap-4">
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
              {sidebarOpen ? '◀' : '▶'}
            </button>
            <h2 className="text-2xl font-bold text-gray-800">Blog Platform Admin</h2>
          </div>

          <div className="flex items-center gap-4">
            <div className="text-right">
              <p className="text-sm font-medium text-gray-900">Admin User</p>
              <p className="text-xs text-gray-500">Conectado</p>
            </div>
            <img
              src="https://via.placeholder.com/40"
              alt="Avatar"
              className="w-10 h-10 rounded-full"
            />
          </div>
        </header>

        {/* Content Area */}
        <main className="flex-1 overflow-y-auto p-6">
          {children}
        </main>
      </div>

      <ToastContainer position="bottom-right" autoClose={3000} />
    </div>
  );
}
