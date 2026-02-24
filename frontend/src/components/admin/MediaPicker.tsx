'use client';

import { useEffect, useState } from 'react';
import { toast } from 'react-toastify';

interface MediaPickerProps {
  onSelect: (url: string) => void;
}

export default function MediaPicker({ onSelect }: MediaPickerProps) {
  const [media, setMedia] = useState<any[]>([]);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const fetchMedia = async () => {
      try {
        const token = localStorage.getItem('auth_token');
        const response = await fetch('/api/v1/media', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
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

    fetchMedia();
  }, []);

  if (isLoading) {
    return <div className="p-4 text-center">Carregando...</div>;
  }

  return (
    <div className="grid grid-cols-4 gap-4">
      {media.map((item) => (
        <div
          key={item.id}
          onClick={() => onSelect(item.url)}
          className="cursor-pointer hover:opacity-75 transition-opacity"
        >
          <img
            src={item.url}
            alt={item.filename}
            className="w-full h-32 object-cover rounded-lg border-2 border-transparent hover:border-blue-500"
          />
          <p className="text-sm text-gray-600 mt-1 truncate">{item.filename}</p>
        </div>
      ))}
    </div>
  );
}
