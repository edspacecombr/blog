import React from 'react';
import Link from 'next/link';

interface RegisterFormProps {
  onSubmit: (email: string, name: string, password: string) => void;
  isLoading?: boolean;
  error?: string;
}

export const RegisterForm: React.FC<RegisterFormProps> = ({ onSubmit, isLoading = false, error }) => {
  const [email, setEmail] = React.useState('');
  const [name, setName] = React.useState('');
  const [password, setPassword] = React.useState('');
  const [confirmPassword, setConfirmPassword] = React.useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (password !== confirmPassword) {
      alert('Passwords do not match');
      return;
    }
    onSubmit(email, name, password);
  };

  return (
    <form onSubmit={handleSubmit} className="w-full max-w-md mx-auto p-6 bg-white rounded-lg shadow">
      <h2 className="text-2xl font-bold mb-6 text-gray-800">Register</h2>

      {error && <div className="mb-4 p-3 bg-red-100 text-red-700 rounded">{error}</div>}

      <div className="mb-4">
        <label className="block text-gray-700 text-sm font-bold mb-2">Name</label>
        <input
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
          required
          className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring"
          placeholder="John Doe"
        />
      </div>

      <div className="mb-4">
        <label className="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
          className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring"
          placeholder="your@email.com"
        />
      </div>

      <div className="mb-4">
        <label className="block text-gray-700 text-sm font-bold mb-2">Password</label>
        <input
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
          className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring"
          placeholder="••••••••"
        />
      </div>

      <div className="mb-6">
        <label className="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
        <input
          type="password"
          value={confirmPassword}
          onChange={(e) => setConfirmPassword(e.target.value)}
          required
          className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring"
          placeholder="••••••••"
        />
      </div>

      <button
        type="submit"
        disabled={isLoading}
        className="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 disabled:opacity-50"
      >
        {isLoading ? 'Registering...' : 'Register'}
      </button>

      <p className="mt-4 text-center text-sm">
        Already have an account?{' '}
        <Link href="/login" className="text-blue-600 hover:underline">
          Login here
        </Link>
      </p>
    </form>
  );
};
