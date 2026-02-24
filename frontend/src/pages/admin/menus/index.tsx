'use client';

import { useState, useEffect } from 'react';
import AdminLayout from '@/components/admin/layouts/AdminLayout';
import { toast } from 'react-toastify';

interface MenuItem {
  id: number;
  label: string;
  url: string;
  order: number;
  parent_id?: number;
}

interface Menu {
  id: number;
  name: string;
  items: MenuItem[];
}

export default function MenusPage() {
  const [menus, setMenus] = useState<Menu[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [selectedMenu, setSelectedMenu] = useState<Menu | null>(null);
  const [showNewItem, setShowNewItem] = useState(false);
  const [newItem, setNewItem] = useState({ label: '', url: '' });

  useEffect(() => {
    fetchMenus();
  }, []);

  const fetchMenus = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/menus', {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (response.ok) {
        const data = await response.json();
        setMenus(data.data);
        if (data.data.length > 0) {
          setSelectedMenu(data.data[0]);
        }
      }
    } catch (error) {
      toast.error('Erro ao carregar menus');
    } finally {
      setIsLoading(false);
    }
  };

  const handleAddItem = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedMenu) return;

    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch(`/api/v1/menus/${selectedMenu.id}/items`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(newItem),
      });

      if (response.ok) {
        toast.success('Item adicionado!');
        setNewItem({ label: '', url: '' });
        setShowNewItem(false);
        fetchMenus();
      }
    } catch (error) {
      toast.error('Erro ao adicionar item');
    }
  };

  const handleDeleteItem = async (itemId: number) => {
    if (!confirm('Deletar item?')) return;
    try {
      const token = localStorage.getItem('auth_token');
      await fetch(`/api/v1/menus/items/${itemId}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
      });
      toast.success('Item deletado!');
      fetchMenus();
    } catch (error) {
      toast.error('Erro ao deletar');
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <h1 className="text-3xl font-bold">📋 Menus</h1>

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <div className="lg:col-span-1">
            <div className="bg-white rounded-lg shadow-md p-4">
              <h2 className="font-bold mb-4">Menus</h2>
              <div className="space-y-2">
                {menus.map((menu) => (
                  <button
                    key={menu.id}
                    onClick={() => setSelectedMenu(menu)}
                    className={`w-full text-left px-4 py-2 rounded-lg transition-colors ${
                      selectedMenu?.id === menu.id
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-100 text-gray-900 hover:bg-gray-200'
                    }`}
                  >
                    {menu.name}
                  </button>
                ))}
              </div>
            </div>
          </div>

          <div className="lg:col-span-3">
            {selectedMenu ? (
              <div className="bg-white rounded-lg shadow-md p-6">
                <div className="flex justify-between items-center mb-4">
                  <h2 className="text-xl font-bold">{selectedMenu.name}</h2>
                  <button
                    onClick={() => setShowNewItem(!showNewItem)}
                    className="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm"
                  >
                    {showNewItem ? '✕' : '➕ Item'}
                  </button>
                </div>

                {showNewItem && (
                  <form onSubmit={handleAddItem} className="mb-6 p-4 bg-blue-50 rounded-lg">
                    <input
                      type="text"
                      value={newItem.label}
                      onChange={(e) => setNewItem({ ...newItem, label: e.target.value })}
                      className="w-full px-4 py-2 border mb-2 rounded-lg"
                      placeholder="Rótulo (ex: Home)"
                      required
                    />
                    <input
                      type="text"
                      value={newItem.url}
                      onChange={(e) => setNewItem({ ...newItem, url: e.target.value })}
                      className="w-full px-4 py-2 border mb-2 rounded-lg"
                      placeholder="URL (ex: /)"
                      required
                    />
                    <button
                      type="submit"
                      className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg"
                    >
                      ✅ Adicionar
                    </button>
                  </form>
                )}

                <div className="space-y-2">
                  {selectedMenu.items.map((item) => (
                    <div
                      key={item.id}
                      className="flex items-center justify-between p-3 bg-gray-50 rounded-lg border-l-4 border-blue-600"
                    >
                      <div>
                        <p className="font-medium">{item.label}</p>
                        <p className="text-sm text-gray-500">{item.url}</p>
                      </div>
                      <button
                        onClick={() => handleDeleteItem(item.id)}
                        className="px-3 py-1 bg-red-600 text-white rounded text-xs"
                      >
                        🗑️
                      </button>
                    </div>
                  ))}
                </div>
              </div>
            ) : (
              <div className="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                Selecione um menu
              </div>
            )}
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
