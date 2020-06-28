import { encrypt } from 'eciesjs'

onmessage = ({ data: { publicKey, content } }) => {
  const contentBuffer = Buffer.from(content)
  const encrypted = encrypt(publicKey, contentBuffer)
  postMessage(encrypted.toString())
}
