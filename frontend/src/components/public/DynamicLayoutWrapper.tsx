'use client';

import { ReactNode } from 'react';
import Layout1Clean from './layouts/Layout1Clean';
import Layout2Magazine from './layouts/Layout2Magazine';
import Layout3Minimal from './layouts/Layout3Minimal';

interface DynamicLayoutWrapperProps {
  children: ReactNode;
  layoutType?: 'Layout1Clean' | 'Layout2Magazine' | 'Layout3Minimal';
}

export default function DynamicLayoutWrapper({
  children,
  layoutType = 'Layout1Clean',
}: DynamicLayoutWrapperProps) {
  // F5.12: Dynamic layout selection based on settings
  const getLayout = () => {
    switch (layoutType) {
      case 'Layout2Magazine':
        return <Layout2Magazine>{children}</Layout2Magazine>;
      case 'Layout3Minimal':
        return <Layout3Minimal>{children}</Layout3Minimal>;
      case 'Layout1Clean':
      default:
        return <Layout1Clean>{children}</Layout1Clean>;
    }
  };

  return getLayout();
}
