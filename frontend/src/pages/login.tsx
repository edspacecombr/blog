import Head from 'next/head';
import { useRouter } from 'next/router';
import React from 'react';
import { LoginForm } from '../components/LoginForm';
import apiClient from '../services/api';
import { useAuthStore } from '../store/auth';

export default function Login() {
  const router = useRouter();
  const [isLoading, setIsLoading] = React.useState(false);
  const [error, setError] = React.useState('');
  const login = useAuthStore((state) => state.login);

  const handleLogin = async (email: string, password: string) => {
    setIsLoading(true);
    setError('');
    try {
      const response = await apiClient.login(email, password);
      if (response.data.success) {
        localStorage.setItem('authToken', response.data.token);
        login(response.data.user, response.data.token);
        router.push('/dashboard');
      } else {
        setError(response.data.message || 'Login failed');
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'An error occurred');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <>
      <Head>
        <title>Login - Professional Blog</title>
      </Head>
      <div className="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4">
        <LoginForm onSubmit={handleLogin} isLoading={isLoading} error={error} />
      </div>
    </>
  );
}
