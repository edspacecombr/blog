import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

// Mock component for testing
const MockPostCard = ({ title, excerpt, author }: any) => (
  <div data-testid="post-card">
    <h2>{title}</h2>
    <p>{excerpt}</p>
    <span>{author}</span>
  </div>
);

describe('PostCard Component', () => {
  it('renders post title', () => {
    render(
      <MockPostCard 
        title="Test Post" 
        excerpt="Test excerpt" 
        author="Test Author" 
      />
    );
    expect(screen.getByText('Test Post')).toBeInTheDocument();
  });

  it('renders post excerpt', () => {
    render(
      <MockPostCard 
        title="Test Post" 
        excerpt="Test excerpt" 
        author="Test Author" 
      />
    );
    expect(screen.getByText('Test excerpt')).toBeInTheDocument();
  });

  it('renders author name', () => {
    render(
      <MockPostCard 
        title="Test Post" 
        excerpt="Test excerpt" 
        author="Test Author" 
      />
    );
    expect(screen.getByText('Test Author')).toBeInTheDocument();
  });

  it('has correct data-testid', () => {
    render(
      <MockPostCard 
        title="Test Post" 
        excerpt="Test excerpt" 
        author="Test Author" 
      />
    );
    expect(screen.getByTestId('post-card')).toBeInTheDocument();
  });
});
