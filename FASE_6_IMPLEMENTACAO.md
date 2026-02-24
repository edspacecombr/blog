# FASE 6 - API REST PÚBLICA + AUTOMAÇÃO

**Objetivos:**
1. Endpoints de criação/atualização de posts
2. Endpoint de publish com revalidação
3. Upload de mídia com Bearer token
4. Webhook de revalidação ISR
5. Rate limiting
6. Documentação OpenAPI

**Estimativa:** 1 semana

---

## F6.1: Criar Post via API Externa

```php
POST /api/v1/posts
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Post Title",
  "slug": "post-slug",
  "description": "Short description",
  "language_id": 1,
  "status": "draft|published|scheduled",
  "scheduled_at": "2026-03-01T10:00:00Z",
  "content": "<p>HTML content</p>",
  "featured_image_url": "https://..."
}
```

---

## F6.2: Atualizar Post

```php
PUT /api/v1/posts/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "description": "Updated description",
  "language_id": 1,
  "content": "<p>Updated content</p>"
}
```

---

## F6.3: Publicar Post (Dispara Revalidação)

```php
PATCH /api/v1/posts/{id}/publish
Authorization: Bearer {token}

Response:
{
  "success": true,
  "message": "Post published",
  "post": {...},
  "revalidated": true
}
```

---

## F6.4: Upload de Mídia

```php
POST /api/v1/media
Authorization: Bearer {token}
Content-Type: multipart/form-data

file: <binary>
alt_text: "Image description"
```

---

## F6.5: Webhook Revalidação

Chamado automaticamente ao publicar post:

```php
POST https://frontend.com/api/revalidate
X-Revalidate-Secret: {secret}

{
  "paths": ["/pt/post/slug", "/en/post/slug", "/es/post/slug"]
}
```

---

## F6.6: Rate Limiting

```php
throttle:60,1   // 60 requests per minute (public endpoints)
throttle:10,1   // 10 requests per minute (upload)
```

---

## F6.7: Documentação OpenAPI

Gerar em `docs/api.md` com exemplos para n8n/Make

