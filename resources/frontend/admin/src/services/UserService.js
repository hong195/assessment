import { BaseResourceService } from '@/services/BaseResourceService'

export default class UserResourceService extends BaseResourceService {
  static get entity () {
    return 'users'
  }
}
