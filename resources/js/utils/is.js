export function isArray(value) {
  return Array.isArray(value)
}

export function isObject(value) {
  return value instanceof Object && value.constructor === Object
}
