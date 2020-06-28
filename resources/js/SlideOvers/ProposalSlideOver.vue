<template>
  <BaseSlideOver
    :is-visible.sync="isVisible"
    :is-rounded="true"
    header-title="Create your Proposal"
    header-description="Get started below by filling in key details, choosing your template & managing access. Publish when ready!"
  >

    <template #body>

    </template>

  </BaseSlideOver>
</template>

<script>
import BaseSlideOver from '@/SlideOvers/BaseSlideOver'
import { Wallet } from '@ethersproject/wallet'
import { HDNode } from '@ethersproject/hdnode'
// import { computePublicKey } from '@ethersproject/signing-key'
// import { Encryptor, Decryptor, Buffer as TriplesecBuffer } from 'triplesec'
// const triplesec = require('triplesec')
// import EthCrypto from 'eth-crypto'
// import { Buffer } from "buffer"
// import { encrypt as ethEncrypt, decrypt as ethDecrypt } from 'eth-ecies'
// import { toBuffer, privateToPublic } from 'ethereumjs-util'
import { encrypt, decrypt } from 'eciesjs'
import EncryptWorker from '@/utils/ecies-encrypt.worker'
// import DecryptWorker from '@/utils/ecies-decrypt.worker'

const encryptWorker = new EncryptWorker()

export default {
  components: { BaseSlideOver },
  data: () => ({
    isVisible: true
  }),
  methods: {
    show () {
      this.isVisible = true
    },
    hide () {
      this.isVisible = false
    }
  },
  async mounted () {
    // console.log(Encryptor, Decryptor)
    // Create wallet/new mnemonic
    const wallet = Wallet.createRandom()
    console.log(wallet)

    const mnemonic = wallet.mnemonic.phrase

    // Get HDNode instance to manipulate
    const masterNode = HDNode.fromMnemonic(mnemonic)


    // Main Account (first)
    const standardEthereum = masterNode.derivePath("m/44'/60'/0'/0/0")
    console.log(standardEthereum)
    // console.log('standardEthereum.publicKey', standardEthereum.publicKey)
    // console.log('standardEthereum.privateKey', standardEthereum.privateKey)

    //account 0 index 1
    const accountZeroIndexOne = masterNode.derivePath("m/44'/60'/0'/0/1");
    console.log(accountZeroIndexOne)
    // console.log('accountZeroIndexOne.publicKey', accountZeroIndexOne.publicKey);
    // console.log('accountZeroIndexOne.privateKey', accountZeroIndexOne.privateKey);
    //
    //

    console.log(accountZeroIndexOne.publicKey)
    // const uncompressedPubKey = computePublicKey(accountZeroIndexOne.publicKey)
    // console.log(uncompressedPubKey)
    // const uncompressedPubKeyBuffer = Buffer.from(accountZeroIndexOne.publicKey, 'hex')
    //


    encryptWorker.postMessage({
      publicKey: accountZeroIndexOne.publicKey,
      content: 'Plaintext'
    })

    encryptWorker.onmessage = ({ data }) => {
      console.log('received from worker: ', data)
    }

    // const plaintextstring = 'Hi this is a plaintext string'
    // const plaintextstringbuffer = Buffer.from(plaintextstring)
    // const encrypted = encrypt(accountZeroIndexOne.publicKey, plaintextstringbuffer)
    // console.log('encrypted: ', encrypted.toString('utf-8'))
    // const decrypted = decrypt(accountZeroIndexOne.privateKey, encrypted)
    // console.log('decrypted: ', decrypted.toString())




    // let plaintext = new Buffer(`{foo:"bar",baz:42}`);
    // console.log(plaintext)
    // console.log(standardEthereum.publicKey)
    // // console.log(Buffer.from('0309a85a3a3d0c016a5c740686ecd7ad7c34358196a08077406418106aa0ead38e', 'hex'))
    // console.log(toBuffer(wallet.publicKey))
    // let encryptedMsg = ethEncrypt(uncompressedPubKeyBuffer, plaintext);


    // Encryption test with triplesec
    // const toRepeat = 'Hello whats up my nibba'
    // const value = `${toRepeat.repeat(1)}`

    // const t0 = performance.now()
    // triplesec.encrypt({key: new triplesec.Buffer('pass'), data: new triplesec.Buffer(value)}, function(err, res){
    //   const t1 = performance.now()
    //   console.log(`Call to doSomething took ${t1 - t0} milliseconds.`);
    //   console.log(err,res)

    //   console.log(res.toString('hex'))

    // });
  }
}
</script>
