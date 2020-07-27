import { isArray, isObject } from './is'

/**
 * Gets the payload data; i.e. retrieve contents of 'data' key.
 *
 * @param {mixed} payload The payload
 * @param {string} [key='data'] The key
 * @return {mixed} mixed The payload data.
 */
export function getPayloadData (payload, key = 'data') {
  if (!payload) return payload

  if (isArray(payload)) {
    return payload
  }

  if (payload[key] && isArray(payload[key])) {
    return payload[key]
  }

  if (payload[key] && isObject(payload[key])) {
    return payload[key]
  }

  return payload
}
