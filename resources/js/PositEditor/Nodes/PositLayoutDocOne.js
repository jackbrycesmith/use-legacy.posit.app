import { Doc } from 'tiptap'

export default class PositLayoutDocOne extends Doc {

  get schema() {
    return {
      content: 'posit_block*',
    }
  }

}
