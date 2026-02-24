'use client';

import { useState } from 'react';
import Link from 'next/link';
import AdminLayout from '@/components/admin/layouts/AdminLayout';

export default function PagesListPage() {
  const [pages, setPages] = useState<any[]>([]);
  const [isLoading, setIsLoading] = useState(true);

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">📄 Páginas Estáticas</h1>
            <p className="text-gray-500 mt-1">Gerencie páginas como Sobre, Contato, Política de Privacidade</p>
          </div>
          <Link
            href="/admin/pages/new"
            className="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
          >
            ➕ Novas Página
          </Link>
        </div>

        <div className="bg-white rounded-lg shadow-md p-6">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <PageCard title="Sobre Nós" type="about" />
            <PageCard title="Contato" type="contact" />
            <PageCard title="Política de Privacidade" type="privacy" />
            <PageCard title="Termos de Serviço" type="terms" />
            <PageCard title="Personalizada" type="custom" />
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}

function PageCard({ title, type }: any) {
  return (
    <div className="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-500 transition-colors">
      <h3 className="font-semibold text-gray-900 mb-2">{title}</h3>
      <p className="text-sm text-gray-500 mb-4">Tipo: {type}</p>
      <div className="flex gap-2">
        <button className="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
          Editar
        </button>
        <button className="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">
          Deletar
        </button>
      </div>
    </div>
  );
}
