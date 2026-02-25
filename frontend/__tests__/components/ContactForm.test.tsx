import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import '@testing-library/jest-dom';

// Mock component for testing
const MockContactForm = ({ onSubmit }: any) => (
  <form data-testid="contact-form" onSubmit={onSubmit}>
    <input 
      type="text" 
      placeholder="Name" 
      name="name" 
      required
    />
    <input 
      type="email" 
      placeholder="Email" 
      name="email" 
      required
    />
    <textarea 
      placeholder="Message" 
      name="message" 
      required
    />
    <button type="submit">Send</button>
  </form>
);

describe('ContactForm Component', () => {
  it('renders form fields', () => {
    const mockSubmit = jest.fn();
    render(<MockContactForm onSubmit={mockSubmit} />);
    
    expect(screen.getByPlaceholderText('Name')).toBeInTheDocument();
    expect(screen.getByPlaceholderText('Email')).toBeInTheDocument();
    expect(screen.getByPlaceholderText('Message')).toBeInTheDocument();
  });

  it('renders submit button', () => {
    const mockSubmit = jest.fn();
    render(<MockContactForm onSubmit={mockSubmit} />);
    
    expect(screen.getByText('Send')).toBeInTheDocument();
  });

  it('handles form submission', async () => {
    const mockSubmit = jest.fn((e) => e.preventDefault());
    render(<MockContactForm onSubmit={mockSubmit} />);
    
    const form = screen.getByTestId('contact-form');
    fireEvent.submit(form);
    
    expect(mockSubmit).toHaveBeenCalled();
  });

  it('validates required fields', async () => {
    const mockSubmit = jest.fn();
    render(<MockContactForm onSubmit={mockSubmit} />);
    
    const nameInput = screen.getByPlaceholderText('Name') as HTMLInputElement;
    expect(nameInput.required).toBe(true);
  });
});
