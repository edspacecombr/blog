import Head from 'next/head';
import { useRouter } from 'next/router';
import React from 'react';
import { RegisterForm } from '../components/RegisterForm';
import apiClient from '../services/api';
import { useAuthStore } from '../store/auth';

export default function Register() {
  const router = useRouter();
  const [isLoading, setIsLoading] = React.useState(false);
  const [error, setError] = React.useState('');
  const login = useAuthStore((state) => state.login);

  const handleRegister = async (email: string, name: string, password: string) => {
    setIsLoading(true);
    setError('');
    try {
      const response = await apiClient.register(email, name, password, 'author');
      if (response.data.success) {
        // Auto-login after registration
        const loginResponse = await apiClient.login(email, password);
        if (loginResponse.data.success) {
          localStorage.setItem('authToken', loginResponse.data.token);
          login(loginResponse.data.user, loginResponse.data.token);
          router.push('/dashboard');
        }
      } else {
        setError(response.data.message || 'Registration failed');
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
        <title>Register - Professional Blog</title>
      </Head>
      <div className="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4">
        <RegisterForm onSubmit={handleRegister} isLoading={isLoading} error={error} />
      </div>
    </>
  );
}
