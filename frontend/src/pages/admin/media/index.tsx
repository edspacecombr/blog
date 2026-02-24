'use client';

import { useState, useEffect } from 'react';
import AdminLayout from '@/components/admin/layouts/AdminLayout';
import { toast } from 'react-toastify';

interface Media {
  id: number;
  filename: string;
  url: string;
  format: string;
  size: number;
  created_at: string;
}

export default function MediaPage() {
  const [media, setMedia] = useState<Media[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [uploadProgress, setUploadProgress] = useState(0);

  useEffect(() => {
    fetchMedia();
  }, []);

  const fetchMedia = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/media', {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (response.ok) {
        const data = await response.json();
        setMedia(data.data);
      }
    } catch (error) {
      toast.error('Erro ao carregar mídia');
    } finally {
      setIsLoading(false);
    }
  };

  const handleUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);

    try {
      const token = localStorage.getItem('auth_token');
      const response = await fetch('/api/v1/media', {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
      });

      if (response.ok) {
        toast.success('Imagem enviada!');
        fetchMedia();
      }
    } catch (error) {
      toast.error('Erro ao enviar imagem');
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Deletar imagem?')) return;
    try {
      const token = localStorage.getItem('auth_token');
      await fetch(`/api/v1/media/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
      });
      toast.success('Imagem deletada!');
      fetchMedia();
    } catch (error) {
      toast.error('Erro ao deletar');
    }
  };

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <h1 className="text-3xl font-bold">🖼️ Biblioteca de Mídia</h1>
          <label className="px-6 py-2 bg-blue-600 text-white rounded-lg cursor-pointer hover:bg-blue-700">
            ⬆️ Upload
            <input
              type="file"
              onChange={handleUpload}
              accept="image/*"
              className="hidden"
            />
          </label>
        </div>

        {uploadProgress > 0 && (
          <div className="bg-white rounded-lg p-4">
            <div className="w-full bg-gray-200 rounded-full h-2">
              <div
                className="bg-blue-600 h-2 rounded-full transition-all"
                style={{ width: `${uploadProgress}%` }}
              ></div>
            </div>
            <p className="text-sm text-gray-600 mt-2">{uploadProgress}%</p>
          </div>
        )}

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          {isLoading
            ? Array(12)
                .fill(0)
                .map((_, i) => (
                  <div
                    key={i}
                    className="bg-gray-200 rounded-lg aspect-square animate-pulse"
                  ></div>
                ))
            : media.map((item) => (
                <div key={item.id} className="group relative">
                  <img
                    src={item.url}
                    alt={item.filename}
                    className="w-full aspect-square object-cover rounded-lg"
                  />
                  <div className="absolute inset-0 bg-black/0 group-hover:bg-black/50 rounded-lg transition-colors flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                    <button
                      onClick={() => {
                        navigator.clipboard.writeText(item.url);
                        toast.info('URL copiada!');
                      }}
                      className="px-3 py-1 bg-blue-600 text-white rounded text-xs"
                    >
                      📋 Copiar
                    </button>
                    <button
                      onClick={() => handleDelete(item.id)}
                      className="px-3 py-1 bg-red-600 text-white rounded text-xs"
                    >
                      🗑️
                    </button>
                  </div>
                  <p className="text-xs text-gray-600 mt-2 truncate">{item.filename}</p>
                  <p className="text-xs text-gray-500">
                    {(item.size / 1024).toFixed(1)}KB • {item.format}
                  </p>
                </div>
              ))}
        </div>
      </div>
    </AdminLayout>
  );
}
