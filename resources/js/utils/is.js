import { isNil } from 'lodash-es'

/**
 * Determines if browser is currently webkit/safari engine.
 *
 * @return {boolean} True if webkit safari, False otherwise.
 * @see as of Sept 2020 (keep an eye on this), Safari is the only one which doesn't support: https://developer.mozilla.org/en-US/docs/Web/API/Permissions_API
 */
export function isWebkitSafari() {
  return isNil(navigator.permissions)
}

export const isDevEnv = process.env.NODE_ENV !== "production"

