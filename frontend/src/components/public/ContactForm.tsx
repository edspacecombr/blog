'use client';

import { useState } from 'react';
import { z } from 'zod';
import { toast } from 'react-toastify';

// F7.2: Contact form with validation and honeypot
const contactSchema = z.object({
  name: z.string().min(3, 'Nome deve ter ao menos 3 caracteres'),
  email: z.string().email('Email inválido'),
  subject: z.string().min(5, 'Assunto deve ter ao menos 5 caracteres'),
  message: z.string().min(10, 'Mensagem deve ter ao menos 10 caracteres'),
  honeypot: z.string().max(0, 'Spam detectado'), // Should be empty
});

type ContactFormData = z.infer<typeof contactSchema>;

interface ContactFormProps {
  locale?: string;
}

export default function ContactForm({ locale = 'pt' }: ContactFormProps) {
  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: '',
    honeypot: '',
  });

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      // Validate form
      const validated = contactSchema.parse(formData);

      // Send to API
      const response = await fetch('/api/v1/contact', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: validated.name,
          email: validated.email,
          subject: validated.subject,
          message: validated.message,
          locale,
        }),
      });

      if (response.ok) {
        toast.success('Mensagem enviada com sucesso!');
        setFormData({ name: '', email: '', subject: '', message: '', honeypot: '' });
      } else {
        toast.error('Erro ao enviar mensagem');
      }
    } catch (error) {
      if (error instanceof z.ZodError) {
        error.issues.forEach((issue) => {
          toast.error(issue.message);
        });
      } else {
        toast.error('Erro desconhecido');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
      <h2 className="text-2xl font-bold mb-6">Entre em Contato</h2>

      {/* Name */}
      <div className="mb-4">
        <label className="block text-gray-700 font-semibold mb-2">Nome</label>
        <input
          type="text"
          name="name"
          value={formData.name}
          onChange={handleChange}
          placeholder="Seu nome"
          className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      {/* Email */}
      <div className="mb-4">
        <label className="block text-gray-700 font-semibold mb-2">Email</label>
        <input
          type="email"
          name="email"
          value={formData.email}
          onChange={handleChange}
          placeholder="seu@email.com"
          className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      {/* Subject */}
      <div className="mb-4">
        <label className="block text-gray-700 font-semibold mb-2">Assunto</label>
        <input
          type="text"
          name="subject"
          value={formData.subject}
          onChange={handleChange}
          placeholder="Assunto"
          className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      {/* Message */}
      <div className="mb-4">
        <label className="block text-gray-700 font-semibold mb-2">Mensagem</label>
        <textarea
          name="message"
          value={formData.message}
          onChange={handleChange}
          placeholder="Sua mensagem aqui..."
          rows={5}
          className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      {/* Honeypot */}
      <input
        type="text"
        name="honeypot"
        value={formData.honeypot}
        onChange={handleChange}
        className="hidden"
        tabIndex={-1}
        autoComplete="off"
      />

      {/* Submit Button */}
      <button
        type="submit"
        disabled={loading}
        className="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
      >
        {loading ? 'Enviando...' : 'Enviar Mensagem'}
      </button>
    </form>
  );
}
