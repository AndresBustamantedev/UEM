const BASE_URL = 'https://thesimpsonsapi.com/api'
const CDN_URL = 'https://cdn.thesimpsonsapi.com/500'

function buildImageUrl(portraitPath) {
  if (!portraitPath) return null
  if (portraitPath.startsWith('http://') || portraitPath.startsWith('https://')) {
    return portraitPath
  }
  if (portraitPath.startsWith('/')) return `${CDN_URL}${portraitPath}`
  return `${CDN_URL}/${portraitPath}`
}

function normalizeCharacter(raw) {
  return {
    ...raw,
    birthdate: raw.birthdate ?? raw.birthday ?? null,
    phrases: raw.phrases ?? raw.quotes ?? [],
    imageUrl: buildImageUrl(raw.portrait_path ?? raw.image ?? raw.portraitPath),
  }
}

async function requestJson(url) {
  const res = await fetch(url)
  if (!res.ok) {
    throw new Error(`Error ${res.status} al cargar datos`)
  }
  return res.json()
}

export async function getAllCharacters(page = 1) {
  const data = await requestJson(`${BASE_URL}/characters?page=${page}`)
  const results = Array.isArray(data.results) ? data.results : []

  return {
    ...data,
    results: results.map(normalizeCharacter),
  }
}

export async function getCharacterById(id) {
  const data = await requestJson(`${BASE_URL}/characters/${id}`)
  return normalizeCharacter(data)
}

export async function searchCharacterByName(name, page = 1) {
  const q = (name ?? '').trim()
  const data = await requestJson(
    `${BASE_URL}/characters?page=${page}&search=${encodeURIComponent(q)}`,
  )

  const results = Array.isArray(data.results) ? data.results : []
  const filtered = q
    ? results.filter((c) => (c.name ?? '').toLowerCase().includes(q.toLowerCase()))
    : results

  return {
    ...data,
    results: filtered.map(normalizeCharacter),
  }
}
