import { each, find, indexOf, isFunction, isArray, isObject } from 'lodash-es'

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

  // This must come after isArray check, because isObject will return true for arrays
  if (payload[key] && isObject(payload[key])) {
    return payload[key]
  }

  return payload
}

/**
 * Appends an or update data.
 *
 * @param {Array} data The data
 * @param {Array} sourceData The source data
 * @param {Function} [transformer=null] The transformer
 * @return {Array} { description_of_the_return_value }
 */
export function appendOrUpdateData (data, sourceData, key = 'id', transformer = null) {
  if (data.constructor !== Array) { data = [data] }

  const canTransform = isFunction(transformer)

  each(data, dataItem => {
    if (canTransform) {
      dataItem = transformer(dataItem)
    }

    var item = find(sourceData, { [key]: dataItem[key] })

    if (item) {
      // If contains item, update item
      Vue.set(sourceData, indexOf(sourceData, item), dataItem)
    } else {
      // Add to list
      sourceData.push(dataItem)
    }
  })

  return sourceData
}
