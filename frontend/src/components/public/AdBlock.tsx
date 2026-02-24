'use client';

import { useEffect } from 'react';

interface AdBlockProps {
  position?: 'top' | 'sidebar' | 'bottom' | 'in-feed';
  locale?: string;
  className?: string;
  enabled?: boolean;
  adSlot?: string;
}

export default function AdBlock({
  position = 'top',
  locale = 'pt',
  className = '',
  enabled = true,
  adSlot = '0000000000',
}: AdBlockProps) {
  // F7.3: AdSense Component - configurable by position and locale
  useEffect(() => {
    if (!enabled) return;

    const script = document.createElement('script');
    script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-0000000000000000';
    script.async = true;
    script.crossOrigin = 'anonymous';
    
    // Load AdSense script if not already loaded
    if (!(window as any).adsbygoogle) {
      document.head.appendChild(script);
    }

    // Push ad when ready
    try {
      ((window as any).adsbygoogle = (window as any).adsbygoogle || []).push({});
    } catch (e) {
      console.error('AdSense error:', e);
    }
  }, [enabled]);

  if (!enabled) return null;

  // Ad sizes by position
  const getSizeClass = () => {
    switch (position) {
      case 'sidebar':
        return 'w-full max-w-xs h-[600px]';
      case 'in-feed':
        return 'w-full h-[250px]';
      case 'bottom':
        return 'w-full max-w-4xl h-[90px]';
      case 'top':
      default:
        return 'w-full max-w-4xl h-[90px]';
    }
  };

  return (
    <div className={`flex justify-center my-6 ${className}`}>
      <ins
        className={`adsbygoogle ${getSizeClass()}`}
        data-ad-client="ca-pub-0000000000000000"
        data-ad-slot={adSlot}
        data-ad-format="auto"
        data-full-width-responsive="true"
      />
    </div>
  );
}
