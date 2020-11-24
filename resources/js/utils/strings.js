import { words, take, upperFirst, toLower, isEmpty } from 'lodash-es'

export const initials = (value, maxInitials = 2) => {
  const wordValues = take(words(value), maxInitials)
  let initialValues = ''
  wordValues.forEach(word => {
    initialValues += word.charAt(0)
  })
  return initialValues.toUpperCase()
}

export const titleCase = (value) => {
  return upperFirst(toLower(value))
}

export const formatCurrency = (amount, currencyCode, maximumFractionDigits = 2, minimumFractionDigits = 0) => {
  const locale = isEmpty(navigator.language) ? 'en' : navigator.language

  let formatted

  try {
    formatted = new Intl.NumberFormat(locale, {
      style: 'currency',
      currency: currencyCode,
      maximumFractionDigits,
      minimumFractionDigits,
    }).format(amount)
  } catch (e) {
    formatted = amount
  }

  return formatted
}
