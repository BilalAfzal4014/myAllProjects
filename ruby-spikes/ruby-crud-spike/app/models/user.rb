class User < ApplicationRecord
  #validates :name, presence: true
  #validates :email, presence: true
  has_one :role, :class_name => 'UserRoleMapping', :foreign_key => 'user_id'

  def self.getUserRoleWithPermissions(id)

    query = ' select users.id, roles.name as role, group_concat(permissions.name) as permissions'
    query += ' from users join user_role_mappings on user_role_mappings.user_id = users.id'
    query += ' join roles on user_role_mappings.role_id = roles.id'
    query += ' join role_permission_mappings on roles.id = role_permission_mappings.role_id'
    query += ' join permissions on role_permission_mappings.permission_id = permissions.id'
    query += ' where users.id = "' + id.to_s + '"'
    query += ' group by users.id, roles.name'

    roleWithPermission = ActiveRecord::Base.connection.exec_query(query)

    user = {
        "id" => roleWithPermission[0]["id"],
        "role" => roleWithPermission[0]["role"],
        "permissions" => {}
    }

    for permission in JSON.parse(roleWithPermission[0]["permissions"].split(',').inspect)
      user["permissions"][permission] = 1
    end

    user

  end

end
