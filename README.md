# 📸 HC IgPulse — Instagram Feed para Joomla

![Status](https://img.shields.io/badge/status-ativo-success)
![Joomla](https://img.shields.io/badge/Joomla-4%20%7C%205%20%7C%206-blue)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-purple)
![API](https://img.shields.io/badge/API-Instagram%20Graph-orange)
![License](https://img.shields.io/badge/license-GPL%20v2%2B-green)

---

## 📌 Visão Geral

Este módulo exibe posts e reels do Instagram diretamente no seu site Joomla, sem frameworks externos e sem scraping.

- 📷 Consome a **Instagram Graph API oficial** (Meta for Developers)
- 🎨 **4 layouts** prontos: Carrossel, Grade, Mosaico e Tira Horizontal
- 📐 **Proporção configurável** por parâmetro: quadrado, paisagem, retrato ou auto
- ↔️ **Espaçamento configurável** com toggle on/off e tamanho em px
- 🖥️ Comportamento diferenciado para **desktop e mobile**
- ⚡ Cache nativo do Joomla via `JCache`
- ♿ HTML semântico com suporte a acessibilidade (ARIA, foco visível)
- 🌍 Bilíngue: **en-GB** e **pt-BR**
- 🔌 Arquitetura **Joomla 4/5/6** moderna: DI Container + Dispatcher + Helper

---

## 📁 Estrutura do Projeto

```
mod_hc_igpulse/
│
├── language/
│   ├── en-GB/
│   │   ├── mod_hc_igpulse.ini        ← Strings de interface (inglês)
│   │   └── mod_hc_igpulse.sys.ini    ← Strings do gerenciador de módulos (inglês)
│   └── pt-BR/
│       ├── mod_hc_igpulse.ini        ← Strings de interface (português)
│       └── mod_hc_igpulse.sys.ini    ← Strings do gerenciador de módulos (português)
│
├── media/
│   └── css/
│       ├── mod_hc_igpulse.css        ← Estilos base — compartilhados por todos os layouts
│       ├── carousel.css              ← Estilos exclusivos do carrossel
│       ├── grid.css                  ← Estilos exclusivos da grade
│       ├── mosaic.css                ← Estilos exclusivos do mosaico
│       └── strip.css                 ← Estilos exclusivos da tira horizontal
│
├── services/
│   └── provider.php                  ← Registro no container DI do Joomla (padrão 4/5/6)
│
├── src/
│   ├── Dispatcher/
│   │   └── Dispatcher.php            ← Prepara e injeta variáveis no layout
│   └── Helper/
│       └── HcIgpulseHelper.php       ← Comunicação com a Graph API + cache + normalização
│
├── tmpl/
│   ├── default.php                   ← Roteador: carrega CSS base + CSS do layout ativo
│   ├── default_carousel.php          ← Template do carrossel (HTML + JS inline)
│   ├── default_grid.php              ← Template da grade
│   ├── default_mosaic.php            ← Template do mosaico
│   └── default_strip.php             ← Template da tira horizontal
│
└── mod_hc_igpulse.xml                ← Manifesto da extensão
```

---

## ⚙️ Requisitos

| Requisito | Versão |
|---|---|
| Joomla | 4.x / 5.x / 6.x |
| PHP | 8.0+ |
| Conta Instagram | Business ou Creator |
| App Meta for Developers | Permissão `instagram_business_basic` |

---

## 🚀 Instalação

1. Baixe o `.zip` da [última release](https://github.com/hirleicarlos/mod_hc_igpulse/releases)
2. No painel Joomla: **Sistema → Instalar → Extensões**
3. Faça upload do `.zip` e clique em **Enviar e Instalar**
4. Vá em **Conteúdo → Módulos do Site**, localize **HC IgPulse — Instagram Feed**
5. Configure as abas abaixo e atribua o módulo a uma posição do template

---

## 🔑 Obter Access Token e User ID

> Você precisa de uma conta **Instagram Business** ou **Creator** vinculada a uma **Página do Facebook**.

### Passo 1 — Criar o app

1. Acesse **https://developers.facebook.com/apps/**
2. Clique em **Criar app** → Caso de uso: **Outros** → Tipo: **Empresa**
3. Informe um nome (ex: `HC Social Feed - Seu Nome`)
4. Após criado: **Adicionar produto → Instagram → Configurar**

### Passo 2 — Gerar o Access Token

1. Acesse: **Instagram → Configuração da API com login do Instagram**
2. Na seção **Gerar token de acesso**, clique em **Gerar** ao lado da sua conta
3. Autorize o app na janela que abrir
4. Copie o token (começa com `IGAA...`)

> 🔗 **https://developers.facebook.com/apps/**

> ⏱️ O token dura **60 dias**. Antes de expirar, repita o processo para gerar um novo.

### Passo 3 — Obter o User ID

1. Acesse o **Depurador de Token**: **https://developers.facebook.com/tools/debug/accesstoken/**
2. Cole o token e clique em **Depurar**
3. Copie o valor de **"ID do usuário no escopo do aplicativo"** (ex: `27124383820526508`)

---

## 🛠 Configuração

### Aba: Autenticação

| Campo | Descrição |
|---|---|
| **Access Token** | Token de longa duração gerado no Meta for Developers |
| **Instagram User ID** | ID numérico da sua conta Instagram Business ou Creator |

---

### Aba: Conteúdo

| Campo | Padrão | Descrição |
|---|---|---|
| **Tipo de Mídia** | Posts e Reels | Posts / Reels / Ambos |
| **Itens para Buscar** | 12 | Total buscado da API (1–20). Todos os layouts usam este total. |

---

### Aba: Layout

| Campo | Aparece em | Padrão | Descrição |
|---|---|---|---|
| **Estilo de Layout** | Sempre | Carrossel | Carrossel / Grade / Mosaico / Tira Horizontal |
| **Visíveis no Desktop** | Somente Carrossel | 4 | Itens exibidos por vez no desktop (máx. 4) |
| **Visíveis no Mobile** | Somente Carrossel | 2 | Itens exibidos por vez no mobile (máx. 2) |
| **Colunas (Desktop)** | Grade / Mosaico | 3 | Colunas no desktop: 2, 3 ou 4 |
| **Colunas (Mobile)** | Grade / Mosaico | 1 | Colunas no mobile: 1 ou 2 |
| **Exibir Legenda** | Sempre | Não | Mostra a legenda do post ao passar o mouse |
| **Exibir Curtidas** | Sempre | Não | Exibe o número de curtidas *(requer permissão no app Meta)* |
| **Abrir Post Em** | Sempre | Instagram | Lightbox (inline) / Nova aba / Instagram (mesma aba) |
| **Exibir Espaçamento** | Sempre | Sim | Ativa o gap entre os itens |
| **Tamanho do Espaçamento** | Quando ativado | 8 | Tamanho do gap em pixels (2–40 px) |
| **Proporção da Imagem** | Sempre | Quadrado | Quadrado (1:1) / Paisagem (16:9) / Retrato (4:5) / Auto |

---

### Aba: Cache

| Campo | Padrão | Descrição |
|---|---|---|
| **Duração do Cache (min)** | 60 | Tempo de cache da resposta da API. Mínimo: 5. Máximo: 1440 (24 h). |

> Para forçar a atualização durante desenvolvimento: **Sistema → Limpar Cache**.

---

## 🎨 Layouts disponíveis

### 🎠 Carrossel

- Exibe N itens por vez com botões de navegação ◀ ▶
- `Visíveis no Desktop`: 1–4 | `Visíveis no Mobile`: 1–2
- Transição suave com `transform: translateX`
- Suporte a teclado e ARIA

### 🔲 Grade

- CSS Grid responsivo com colunas configuráveis
- Exibe todos os itens buscados (definidos em **Itens para Buscar**)
- Desktop: 2, 3 ou 4 colunas | Mobile: 1 ou 2 colunas

### 🎨 Mosaico

- Grade CSS com o **primeiro item em destaque** (span 2×2 no desktop)
- Exibe todos os itens buscados
- Item destacado colapsa para 1×1 no mobile

### ➡️ Tira Horizontal

- Fileira horizontal com rolagem suave e CSS scroll snap
- Itens com **240 px de largura fixa** — sem conceito de colunas
- Exibe todos os itens buscados; o usuário rola para ver os demais

---

## 📐 Proporção das imagens

| Valor | Proporção | Comportamento |
|---|---|---|
| **Quadrado** | 1:1 | Recorte centralizado em quadrado. Padrão. |
| **Paisagem** | 16:9 | Recorte widescreen. |
| **Retrato** | 4:5 | Formato vertical. |
| **Auto** | Natural | Sem recorte — exibe na proporção original da imagem. |

> Use **Auto** quando seus posts têm formatos variados ou quando não quer perder nenhuma parte da imagem.

---

## 🔄 Fluxo de dados

```
Instagram Graph API
        │
        ▼
HcIgpulseHelper::fetchFromApi()
  ├─ HttpFactory::getHttp() — sem file_get_contents
  └─ Filtra por media_type (IMAGE / VIDEO / ALL)
        │
        ▼
JCache — grupo: mod_hc_igpulse
  └─ TTL configurável (padrão: 60 min)
        │
        ▼
Dispatcher::getLayoutData()
  └─ Injeta: $items, $colsDesktop, $colsMobile,
             $countDesktop, $countMobile,
             $gapEnabled, $gapSize, $aspectRatio,
             $showCaption, $showLikes, $openIn, $layoutStyle
        │
        ▼
tmpl/default.php
  ├─ HTMLHelper::_('stylesheet', 'mod_hc_igpulse/mod_hc_igpulse.css')
  ├─ HTMLHelper::_('stylesheet', 'mod_hc_igpulse/{layout}.css')
  └─ ModuleHelper::getLayoutPath → default_{layout}.php
        │
        ▼
HTML renderizado no frontend
```

---

## 🔌 Update Server

O módulo registra um servidor de atualização automático no manifesto.
O Joomla verifica novas versões em:

```
https://hirleicarlos.github.io/updates/mod_hc_igpulse.xml
```

> Quando uma nova versão for publicada, o Joomla exibirá o aviso de atualização no painel de extensões.

---

## 🧾 Princípios de Engenharia

| Princípio | Aplicação |
|---|---|
| Arquitetura Joomla 4/5/6 | DI Container + AbstractModuleDispatcher + HelperFactoryAwareInterface |
| Sem dependências externas | Nenhum framework CSS ou JS de terceiros |
| CSS por escopo | 1 arquivo base + 1 arquivo por layout, carregados sob demanda |
| Variáveis CSS inline | Gap e colunas injetados via `style` na `<section>` |
| Proporção via atributo | `data-hc-ratio` no elemento raiz, CSS responde com seletores de atributo |
| Sem `file_get_contents` | API consumida via `HttpFactory::getHttp()` do Joomla |
| Cache nativo | `CacheControllerFactoryInterface` — sem cache externo |
| HTML semântico | `<section>`, `<ul>`, `<figure>`, `<figcaption>`, ARIA labels, `focus-visible` |
| Bilíngue | en-GB + pt-BR — `.ini` e `.sys.ini` |

---

## 📬 Contato

- 🌐 Site: [hirleicarlos.github.io](https://hirleicarlos.github.io)
- 💼 LinkedIn: [linkedin.com/in/hirleicarlos](https://linkedin.com/in/hirleicarlos)
- 🐙 GitHub: [github.com/hirleicarlos](https://github.com/hirleicarlos)
- ✉ E-mail: prof.hirleicarlos@gmail.com

---

© 2026 — Hirlei Carlos Pereira de Araújo
Desenvolvedor Full Stack Sênior | PHP & Joomla | Sistemas Corporativos | Governo e Educação
