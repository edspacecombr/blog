import Head from 'next/head';
import { useRouter } from 'next/router';
import React from 'react';
import { useAuthStore } from '../store/auth';

export default function Dashboard() {
  const router = useRouter();
  const { user, isAuthenticated } = useAuthStore();

  React.useEffect(() => {
    if (!isAuthenticated) {
      router.push('/login');
    }
  }, [isAuthenticated, router]);

  if (!isAuthenticated) {
    return <div>Loading...</div>;
  }

  const handleLogout = () => {
    useAuthStore.setState({ user: null, token: null, isAuthenticated: false });
    localStorage.removeItem('authToken');
    router.push('/');
  };

  return (
    <>
      <Head>
        <title>Dashboard - Professional Blog</title>
      </Head>
      <div className="min-h-screen bg-gray-50">
        <nav className="bg-white shadow-md">
          <div className="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 className="text-2xl font-bold text-blue-600">Blog Admin</h1>
            <div className="flex items-center gap-4">
              <span className="text-gray-700">{user?.name}</span>
              <button onClick={handleLogout} className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Logout
              </button>
            </div>
          </div>
        </nav>

        <div className="max-w-6xl mx-auto px-4 py-12">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div className="bg-white rounded-lg shadow p-6">
              <h3 className="text-gray-500 text-sm uppercase">Posts</h3>
              <p className="text-4xl font-bold text-blue-600">0</p>
            </div>
            <div className="bg-white rounded-lg shadow p-6">
              <h3 className="text-gray-500 text-sm uppercase">Categories</h3>
              <p className="text-4xl font-bold text-green-600">0</p>
            </div>
            <div className="bg-white rounded-lg shadow p-6">
              <h3 className="text-gray-500 text-sm uppercase">Pages</h3>
              <p className="text-4xl font-bold text-purple-600">0</p>
            </div>
            <div className="bg-white rounded-lg shadow p-6">
              <h3 className="text-gray-500 text-sm uppercase">Views</h3>
              <p className="text-4xl font-bold text-orange-600">0</p>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <DashboardCard title="Create New Post" description="Start writing your first post" href="/dashboard/posts/new" />
            <DashboardCard title="Manage Categories" description="Organize your content" href="/dashboard/categories" />
            <DashboardCard title="Media Library" description="Manage images and files" href="/dashboard/media" />
          </div>
        </div>
      </div>
    </>
  );
}

function DashboardCard({ title, description, href }: { title: string; description: string; href: string }) {
  return (
    <a
      href={href}
      className="bg-white rounded-lg shadow p-6 hover:shadow-lg transition cursor-pointer block"
    >
      <h3 className="text-lg font-bold text-gray-800 mb-2">{title}</h3>
      <p className="text-gray-600 text-sm">{description}</p>
    </a>
  );
}
