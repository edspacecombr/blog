# API REST Pública - Fase 6

**Base URL:** `http://localhost:8000/api/v1`  
**Authentication:** Bearer Token

---

## Autenticação

### Obter Token de Automação

```http
POST /auth/login HTTP/1.1
Content-Type: application/json

{
  "email": "automation@example.com",
  "password": "automation_password"
}

Response:
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

---

## F6.1: Criar Post

```http
POST /posts HTTP/1.1
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "title": "Novo Artigo",
  "slug": "novo-artigo",
  "excerpt": "Resumo curto do artigo",
  "content": "<p>Conteúdo HTML do artigo...</p>",
  "language": "pt",
  "status": "draft",
  "author_id": 1,
  "featured_image_id": null,
  "seo_title": "SEO Title",
  "seo_description": "SEO description",
  "seo_keywords": "keyword1,keyword2"
}

Response (201):
{
  "data": {
    "id": 1,
    "title": "Novo Artigo",
    "slug": "novo-artigo",
    "status": "draft",
    "created_at": "2026-02-24T21:45:00Z"
  }
}
```

---

## F6.2: Atualizar Post

```http
PUT /posts/{id} HTTP/1.1
Authorization: Bearer {access_token}
Content-Type: application/json

{
  "title": "Artigo Atualizado",
  "content": "<p>Conteúdo atualizado...</p>",
  "language": "pt"
}

Response (200):
{
  "data": {
    "id": 1,
    "title": "Artigo Atualizado",
    "updated_at": "2026-02-24T21:46:00Z"
  }
}
```

---

## F6.3: Publicar Post (Dispara Revalidação ISR)

```http
PATCH /posts/{id}/publish HTTP/1.1
Authorization: Bearer {access_token}

Response (200):
{
  "data": {
    "post": {
      "id": 1,
      "title": "Novo Artigo",
      "status": "published",
      "published_at": "2026-02-24T21:47:00Z"
    },
    "revalidated": true
  },
  "message": "Post published successfully"
}
```

---

## F6.4: Upload de Mídia

```http
POST /media HTTP/1.1
Authorization: Bearer {access_token}
Content-Type: multipart/form-data

file: <binary>
alt_text[pt]: "Descrição em português"
alt_text[en]: "Description in English"

Response (201):
{
  "data": {
    "id": 1,
    "name": "image.jpg",
    "url": "https://storage.example.com/media/image.jpg",
    "webp_url": "https://storage.example.com/media/image.webp",
    "avif_url": "https://storage.example.com/media/image.avif",
    "size": 1024000,
    "mime_type": "image/jpeg"
  }
}
```

---

## F6.5: Webhook de Revalidação ISR

Chamado automaticamente pelo backend ao publicar post.

```http
POST https://frontend.example.com/api/revalidate HTTP/1.1
X-Revalidate-Secret: {revalidate_secret}
Content-Type: application/json

{
  "paths": [
    "/pt/post/novo-artigo",
    "/en/post/novo-artigo",
    "/es/post/novo-artigo"
  ]
}

Response (200):
{
  "revalidated": true,
  "paths": [...],
  "now": 1708879800000
}
```

---

## F6.6: Rate Limiting

- **Public Endpoints:** 60 requests/minute
- **Media Upload:** 10 requests/minute
- **All Endpoints:** Return `X-RateLimit-*` headers

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1708879860
```

---

## Exemplos com n8n/Make

### n8n Workflow: Criar Post Automaticamente

1. **Trigger:** Schedule (diariamente)
2. **Node 1:** HTTP GET - Fetch content from source
3. **Node 2:** HTTP POST - Create post
   - URL: `http://api.example.com/api/v1/posts`
   - Auth: Bearer Token
   - Body: JSON com dados
4. **Node 3:** HTTP PATCH - Publish post
   - URL: `http://api.example.com/api/v1/posts/{{ $json.data.id }}/publish`
   - Auth: Bearer Token

### Make Webhook: Publicar Quando Evento Ocorre

```
Trigger: When Request Received
  - URL: https://hook.make.com/...

Modules:
1. HTTP Request - Create post
2. HTTP Request - Publish post (PATCH)
```

---

## Tratamento de Erros

```json
{
  "error": "Validation error",
  "message": "Title is required",
  "code": 422
}
```

**Status Codes:**
- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `429` - Rate Limited
- `500` - Server Error

---

## Ambiente

### Development (.env)

```
API_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000
REVALIDATE_SECRET=dev_secret_key
```

### Production (.env)

```
API_URL=https://api.example.com
FRONTEND_URL=https://example.com
REVALIDATE_SECRET=$(openssl rand -hex 32)
AUTOMATION_TOKEN=$(php artisan sanctum:create-token automation --plain)
```

---

## Segurança

1. **HTTPS Obrigatório** em produção
2. **CORS Restrito** a frontend domain
3. **Rate Limiting** em todos endpoints
4. **Token Rotation** a cada 30 dias
5. **API Logging** com masking de dados sensíveis

---

Documentação gerada: 2026-02-24 21:50 UTC
