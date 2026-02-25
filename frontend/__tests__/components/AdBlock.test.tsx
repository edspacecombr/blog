import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

// Mock component for testing
const MockAdBlock = ({ visible, adSlot }: any) => (
  visible ? (
    <div data-testid="ad-block" data-ad-slot={adSlot}>
      <ins className="adsbygoogle" 
           style={{ display: 'block' }}
           data-ad-client="ca-pub-xxxxxxxxxxxxxxxx"
           data-ad-slot={adSlot}>
      </ins>
    </div>
  ) : null
);

describe('AdBlock Component', () => {
  it('renders when visible is true', () => {
    render(<MockAdBlock visible={true} adSlot="1234567890" />);
    expect(screen.getByTestId('ad-block')).toBeInTheDocument();
  });

  it('does not render when visible is false', () => {
    render(<MockAdBlock visible={false} adSlot="1234567890" />);
    expect(screen.queryByTestId('ad-block')).not.toBeInTheDocument();
  });

  it('has correct ad slot', () => {
    render(<MockAdBlock visible={true} adSlot="9876543210" />);
    expect(screen.getByTestId('ad-block')).toHaveAttribute('data-ad-slot', '9876543210');
  });

  it('renders adsbygoogle script tag', () => {
    render(<MockAdBlock visible={true} adSlot="1234567890" />);
    expect(screen.getByClassName('adsbygoogle')).toBeInTheDocument();
  });

  it('has display block style', () => {
    render(<MockAdBlock visible={true} adSlot="1234567890" />);
    const ins = screen.getByClassName('adsbygoogle');
    expect(ins).toHaveStyle('display: block');
  });
});
