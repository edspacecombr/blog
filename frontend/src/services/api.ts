import axios, { AxiosInstance } from 'axios';

class ApiClient {
  private client: AxiosInstance;

  constructor() {
    this.client = axios.create({
      baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    this.client.interceptors.request.use((config) => {
      const token = typeof window !== 'undefined' ? localStorage.getItem('authToken') : null;
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    });
  }

  async register(email: string, name: string, password: string, role: string = 'author') {
    return this.client.post('/api/v1/auth/register', {
      email,
      name,
      password,
      role,
    });
  }

  async login(email: string, password: string) {
    return this.client.post('/api/v1/auth/login', {
      email,
      password,
    });
  }

  async validateToken() {
    return this.client.get('/api/v1/auth/validate');
  }

  async health() {
    return this.client.get('/api/v1/health');
  }
}

export default new ApiClient();
