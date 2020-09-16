import Cookies from 'js-cookie'

export default function getCookieNamed(name) {
  return Cookies.get(name)
}
