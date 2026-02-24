'use client';

import { useState, useEffect } from 'react';
import AdminLayout from '@/components/admin/layouts/AdminLayout';
import { toast } from 'react-toastify';

interface Language {
  id: number;
  code: string;
  name: string;
  native_name: string;
  is_default: boolean;
  is_active: boolean;
}

export default function LanguagesPage() {
  const [languages, setLanguages] = useState<Language[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [newLang, setNewLang] = useState({
    code: '',
    name: '',
    native_name: '',
  });

  useEffect(() => {
    fetchLanguages();
  }, []);

  const fetchLanguages = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/languages', {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (response.ok) {
        const data = await response.json();
        setLanguages(data.data);
      }
    } catch (error) {
      toast.error('Erro ao carregar idiomas');
    } finally {
      setIsLoading(false);
    }
  };

  const handleAddLanguage = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/languages', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(newLang),
      });

      if (response.ok) {
        toast.success('Idioma adicionado!');
        setNewLang({ code: '', name: '', native_name: '' });
        fetchLanguages();
      }
    } catch (error) {
      toast.error('Erro ao adicionar idioma');
    }
  };

  const handleToggleDefault = async (id: number) => {
    try {
      const token = localStorage.getItem('auth_token');
      await fetch(`/api/v1/languages/${id}/default`, {
        method: 'PATCH',
        headers: { Authorization: `Bearer ${token}` },
      });
      toast.success('Idioma padrão alterado!');
      fetchLanguages();
    } catch (error) {
      toast.error('Erro ao alterar idioma padrão');
    }
  };

  const handleToggleActive = async (id: number) => {
    try {
      const token = localStorage.getItem('auth_token');
      await fetch(`/api/v1/languages/${id}/toggle`, {
        method: 'PATCH',
        headers: { Authorization: `Bearer ${token}` },
      });
      toast.success('Status alterado!');
      fetchLanguages();
    } catch (error) {
      toast.error('Erro ao alterar status');
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <h1 className="text-3xl font-bold">🌍 Gestão de Idiomas</h1>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div className="lg:col-span-2">
            {isLoading ? (
              <div className="bg-white p-6 text-center text-gray-500 rounded-lg">
                Carregando...
              </div>
            ) : (
              <div className="bg-white rounded-lg shadow-md overflow-hidden">
                <table className="w-full">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Código</th>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Nome</th>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Nome Nativo</th>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Status</th>
                      <th className="px-6 py-3 text-left text-sm font-semibold">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    {languages.map((lang) => (
                      <tr key={lang.id} className="border-b hover:bg-gray-50">
                        <td className="px-6 py-4 font-mono text-sm font-bold">{lang.code}</td>
                        <td className="px-6 py-4 text-sm">{lang.name}</td>
                        <td className="px-6 py-4 text-sm">{lang.native_name}</td>
                        <td className="px-6 py-4">
                          <div className="flex gap-2">
                            {lang.is_default && (
                              <span className="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                ⭐ Padrão
                              </span>
                            )}
                            {lang.is_active ? (
                              <span className="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                ✅ Ativo
                              </span>
                            ) : (
                              <span className="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">
                                ❌ Inativo
                              </span>
                            )}
                          </div>
                        </td>
                        <td className="px-6 py-4 flex gap-2">
                          {!lang.is_default && (
                            <button
                              onClick={() => handleToggleDefault(lang.id)}
                              className="px-2 py-1 bg-blue-600 text-white rounded text-xs"
                            >
                              ⭐ Padrão
                            </button>
                          )}
                          <button
                            onClick={() => handleToggleActive(lang.id)}
                            className={`px-2 py-1 rounded text-xs text-white ${
                              lang.is_active
                                ? 'bg-red-600 hover:bg-red-700'
                                : 'bg-green-600 hover:bg-green-700'
                            }`}
                          >
                            {lang.is_active ? '❌ Desativar' : '✅ Ativar'}
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
            <h2 className="text-lg font-bold mb-4">➕ Novo Idioma</h2>
            <form onSubmit={handleAddLanguage} className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-1">Código (ISO 639-1) *</label>
                <input
                  type="text"
                  value={newLang.code}
                  onChange={(e) => setNewLang({ ...newLang, code: e.target.value.slice(0, 2) })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  placeholder="pt, en, es"
                  maxLength={2}
                  required
                />
              </div>

              <div>
                <label className="block text-sm font-medium mb-1">Nome *</label>
                <input
                  type="text"
                  value={newLang.name}
                  onChange={(e) => setNewLang({ ...newLang, name: e.target.value })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  placeholder="Portuguese"
                  required
                />
              </div>

              <div>
                <label className="block text-sm font-medium mb-1">Nome Nativo *</label>
                <input
                  type="text"
                  value={newLang.native_name}
                  onChange={(e) => setNewLang({ ...newLang, native_name: e.target.value })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  placeholder="Português"
                  required
                />
              </div>

              <button
                type="submit"
                className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
              >
                ✅ Adicionar
              </button>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
