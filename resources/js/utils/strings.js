import { words, take, upperFirst, toLower } from 'lodash-es'

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
