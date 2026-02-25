# FASE 7 - AdSense + Performance + Conformidade

**Objetivos:**
1. Componente AdSense configurável
2. Páginas essenciais (privacy, terms, about, contact)
3. Formulário de contato
4. Otimização Core Web Vitals
5. HTTPS + HSTS

**Estimativa:** 2 semanas

---

## F7.1: Gerador de Páginas Essenciais

Seed que cria 4 páginas do tipo system:
- Privacy Policy
- Terms of Service
- About Us
- Contact

---

## F7.2: Formulário de Contato

Componente React com:
- Validação Zod
- Honeypot anti-spam
- Envio via POST /api/v1/contact
- Email via SMTP

---

## F7.3: Componente AdSense

```tsx
<AdBlock 
  position="top" 
  locale={locale}
  enabled={settings.monetization_enabled}
/>
```

Configurável por:
- Idioma
- Posição (top, sidebar, bottom)
- Tipo (display, in-feed, matched content)

---

## F7.4-F7.5: Performance

- Gzip/Brotli compression
- Code splitting automático
- Bundle analysis
- Image optimization (WebP/AVIF)

---

## F7.6-F7.7: Security + Web Vitals

- HTTPS enforced
- HSTS headers
- CSP headers
- Lighthouse 90+ score

