'use client';

import { useState, useEffect } from 'react';
import AdminLayout from '@/components/admin/layouts/AdminLayout';
import { toast } from 'react-toastify';

interface Settings {
  blog_name: string;
  blog_url: string;
  blog_description: string;
  blog_email: string;
  logo_url: string;
  favicon_url: string;
  primary_color: string;
  secondary_color: string;
  active_layout: string;
  adsense_id: string;
  robots_txt: string;
}

export default function SettingsPage() {
  const [settings, setSettings] = useState<Settings>({
    blog_name: '',
    blog_url: '',
    blog_description: '',
    blog_email: '',
    logo_url: '',
    favicon_url: '',
    primary_color: '#3B82F6',
    secondary_color: '#10B981',
    active_layout: 'Layout1Clean',
    adsense_id: '',
    robots_txt: '',
  });
  const [isSaving, setIsSaving] = useState(false);

  useEffect(() => {
    fetchSettings();
  }, []);

  const fetchSettings = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/settings', {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (response.ok) {
        const data = await response.json();
        setSettings(data.data);
      }
    } catch (error) {
      toast.error('Erro ao carregar configurações');
    }
  };

  const handleSave = async () => {
    setIsSaving(true);
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/settings', {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(settings),
      });

      if (response.ok) {
        toast.success('Configurações salvas!');
        fetchSettings();
      }
    } catch (error) {
      toast.error('Erro ao salvar configurações');
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <h1 className="text-3xl font-bold">⚙️ Configurações Globais</h1>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div className="lg:col-span-2 space-y-6">
            {/* Informações Básicas */}
            <div className="bg-white rounded-lg shadow-md p-6">
              <h2 className="text-xl font-bold mb-4">📝 Informações Básicas</h2>
              <div className="space-y-4">
                <input
                  type="text"
                  value={settings.blog_name}
                  onChange={(e) => setSettings({ ...settings, blog_name: e.target.value })}
                  placeholder="Nome do Blog"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                />
                <input
                  type="url"
                  value={settings.blog_url}
                  onChange={(e) => setSettings({ ...settings, blog_url: e.target.value })}
                  placeholder="URL do Blog"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                />
                <textarea
                  value={settings.blog_description}
                  onChange={(e) => setSettings({ ...settings, blog_description: e.target.value })}
                  placeholder="Descrição do Blog"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  rows={3}
                />
                <input
                  type="email"
                  value={settings.blog_email}
                  onChange={(e) => setSettings({ ...settings, blog_email: e.target.value })}
                  placeholder="Email de Contato"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                />
              </div>
            </div>

            {/* Design */}
            <div className="bg-white rounded-lg shadow-md p-6">
              <h2 className="text-xl font-bold mb-4">🎨 Design</h2>
              <div className="space-y-4">
                <input
                  type="url"
                  value={settings.logo_url}
                  onChange={(e) => setSettings({ ...settings, logo_url: e.target.value })}
                  placeholder="URL da Logo"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                />
                <div className="flex gap-4">
                  <div className="flex-1">
                    <label className="block text-sm mb-1">Cor Primária</label>
                    <div className="flex gap-2">
                      <input
                        type="color"
                        value={settings.primary_color}
                        onChange={(e) => setSettings({ ...settings, primary_color: e.target.value })}
                        className="w-12 h-10 rounded cursor-pointer"
                      />
                      <input
                        type="text"
                        value={settings.primary_color}
                        onChange={(e) => setSettings({ ...settings, primary_color: e.target.value })}
                        className="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                      />
                    </div>
                  </div>
                  <div className="flex-1">
                    <label className="block text-sm mb-1">Cor Secundária</label>
                    <div className="flex gap-2">
                      <input
                        type="color"
                        value={settings.secondary_color}
                        onChange={(e) => setSettings({ ...settings, secondary_color: e.target.value })}
                        className="w-12 h-10 rounded cursor-pointer"
                      />
                      <input
                        type="text"
                        value={settings.secondary_color}
                        onChange={(e) => setSettings({ ...settings, secondary_color: e.target.value })}
                        className="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                      />
                    </div>
                  </div>
                </div>
                <select
                  value={settings.active_layout}
                  onChange={(e) => setSettings({ ...settings, active_layout: e.target.value })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                >
                  <option value="Layout1Clean">Layout 1: Clean Editorial</option>
                  <option value="Layout2Magazine">Layout 2: Magazine</option>
                  <option value="Layout3Minimal">Layout 3: Minimal Blog</option>
                </select>
              </div>
            </div>

            {/* SEO e Publicidade */}
            <div className="bg-white rounded-lg shadow-md p-6">
              <h2 className="text-xl font-bold mb-4">🔍 SEO e Publicidade</h2>
              <div className="space-y-4">
                <input
                  type="text"
                  value={settings.adsense_id}
                  onChange={(e) => setSettings({ ...settings, adsense_id: e.target.value })}
                  placeholder="ID AdSense"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg"
                />
                <textarea
                  value={settings.robots_txt}
                  onChange={(e) => setSettings({ ...settings, robots_txt: e.target.value })}
                  placeholder="Conteúdo robots.txt"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm"
                  rows={6}
                />
              </div>
            </div>

            <button
              onClick={handleSave}
              disabled={isSaving}
              className="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 font-bold"
            >
              {isSaving ? '⏳ Salvando...' : '✅ Salvar Configurações'}
            </button>
          </div>

          {/* Preview */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-lg font-bold mb-4">👁️ Preview</h2>
            <div
              className="p-4 rounded-lg text-white"
              style={{
                backgroundColor: settings.primary_color,
              }}
            >
              <div className="flex items-center gap-3 mb-4">
                {settings.logo_url && (
                  <img
                    src={settings.logo_url}
                    alt="Logo"
                    className="w-12 h-12 rounded object-cover"
                  />
                )}
                <div>
                  <h3 className="font-bold">{settings.blog_name || 'Blog'}</h3>
                  <p className="text-sm opacity-90">
                    {settings.blog_description || 'Descrição...'}
                  </p>
                </div>
              </div>
              <div
                className="w-full h-32 rounded mb-4 opacity-50"
                style={{ backgroundColor: settings.secondary_color }}
              ></div>
              <p className="text-xs opacity-75">Layout: {settings.active_layout}</p>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
