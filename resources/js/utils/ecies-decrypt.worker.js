import { decrypt } from 'eciesjs'

onmessage = ({ data: { privateKey, encryptedContent } }) => {
  const encryptedContentBuffer = Buffer.from(encryptedContent)
  const decrypted = decrypt(privateKey, encryptedContentBuffer)
  postMessage(decrypted.toString())
}
