class RoleServices

  def fetchRoles
    Role.all
  end

  def assignRoleToUser(role_id, user_id)
    UserRoleMapping.create(user_id: user_id, role_id: role_id)
  end

  def udpateRoleOfUser(role_id, user_id)
    UserRoleMapping.where(user_id: user_id).update_all(role_id: role_id)
    #userRoleMapping = UserRoleMapping.where(user_id: user_id) #will return array of objects
    #userRoleMapping[0].role_id
    #array of objects are like [<name: "Bilal", password: "abc">]
    #array of hashes are like [{"name" => "Bilal", "password" => "abc"}]
  end

end