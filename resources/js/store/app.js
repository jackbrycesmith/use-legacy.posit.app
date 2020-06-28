const getDefaultState = () => ({
})

export default {
  namespaced: true,
  state: getDefaultState(),
  mutations: {
     reset: state => {
       Object.assign(state, getDefaultState())
    }
  }
}
