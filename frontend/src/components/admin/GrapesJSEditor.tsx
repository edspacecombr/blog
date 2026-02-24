'use client';

import React, { useEffect, useRef, useState } from 'react';
import grapesjs from 'grapesjs';
import GjsPresetWebpage from 'grapesjs-preset-webpage';

interface GrapesJSEditorProps {
  initialContent?: string;
  initialData?: any;
  onChange?: (html: string, data: any) => void;
  onSave?: (html: string, data: any) => void;
}

export default function GrapesJSEditor({
  initialContent = '<p>Comece a editar...</p>',
  initialData = {},
  onChange,
  onSave,
}: GrapesJSEditorProps) {
  const editorRef = useRef<HTMLDivElement>(null);
  const editorInstanceRef = useRef<any>(null);
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    if (!editorRef.current || isLoaded) return;

    try {
      const editor = grapesjs.init({
        container: editorRef.current,
        fromElement: false,
        height: '600px',
        width: '100%',
        showOffsets: true,
        noticeOnUnload: true,
        storageManager: { type: '' },
        plugins: [GjsPresetWebpage as any],
        pluginsOpts: {
          'grapesjs-preset-webpage': {},
        },
      });

      editor.setComponents(initialContent);
      if (Object.keys(initialData).length > 0) {
        editor.setStyle(initialData.style || {});
      }

      editor.on('change', () => {
        if (onChange) {
          const html = editor.getHtml();
          const data = editor.getStyle();
          onChange(html, data);
        }
      });

      (window as any).saveEditorContent = () => {
        if (onSave) {
          const html = editor.getHtml();
          const data = editor.getStyle();
          onSave(html, data);
        }
      };

      editorInstanceRef.current = editor;
      setIsLoaded(true);
    } catch (error) {
      console.error('Erro ao inicializar GrapesJS:', error);
    }

    return () => {
      if (editorInstanceRef.current) {
        try {
          editorInstanceRef.current.destroy();
        } catch {}
      }
    };
  }, []);

  const handleSave = () => {
    if (editorInstanceRef.current && onSave) {
      const html = editorInstanceRef.current.getHtml();
      const data = editorInstanceRef.current.getStyle();
      onSave(html, data);
    }
  };

  return (
    <div className="grapesjs-wrapper">
      <div className="mb-4 flex gap-2">
        <button
          onClick={handleSave}
          className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Salvar Conteúdo
        </button>
      </div>
      <div
        ref={editorRef}
        id="gjs"
        style={{ height: '600px', border: '1px solid #ddd' }}
      />
    </div>
  );
}
