import Http from '@/utils/Http'
import authConfig from '@/store/user/utils'

export class BaseResourceService {
  static get entity () {
    throw new Error('entity getter not defined')
  }

  constructor () {
    this.requester = new Http()
  }

  createResource (params) {
    return this.requester.post(this.entity, params, authConfig())
  }

  updateResource (id, params) {
    const url = this.entity + '/' + id
    return this.requester.put(url, params, authConfig())
  }

  deleteResourceById (id) {
    const url = this.entity + '/' + id
    return this.requester.delete(url, {}, authConfig())
  }

  getResourceById (id) {
    return this.requester.get(id)
  }

  getResourceList (params = {}) {
    return this.requester.get(this.entity, params, authConfig())
  }
}
