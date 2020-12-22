require_relative '../role_service/role_service';

class UserServices
  def fetchUsers
    User.all
  end

  def createUser(userProps)
    user = User.new()
    assignValuesToUser(user, userProps)
    user.id
  end

  def updateUser(userProps)
    user = fetchUser(userProps["mode"])
    assignValuesToUser(user, userProps)
  end

  def fetchUser(userId)
    User.find(userId)
  end

  def assignValuesToUser(user, userProps)
    user.name = userProps["name"]
    user.email = userProps["email"]
    if userProps["password"] != ""
      user.password = BCrypt::Password.create(userProps["password"])
    end
    user.save()
    user.id
  end

  def fetchUserByKey(key, value)
    user = User.where("#{key}": value)
    user[0]
  end

  def getUserRoleWithPermissions(id)
    User.getUserRoleWithPermissions(id)
  end

  def userRequestValidation(user)

    errors = {
        "name" => "",
        "email" => "",
        "password" => "",
        "role" => "",
        "error" => false
    }

    if !user.key?("name") or user["name"] == ""
      errors["name"] = "Name is required"
      errors["error"] = true
    end

    if !user.key?("email") or user["email"] == ""
      errors["email"] = "Email is required"
      errors["error"] = true
    else
      isGotRecord = User.where("email": user["email"]).where.not(id: user["mode"])
      if isGotRecord.length > 0
        errors["email"] = "Email is duplicate"
        errors["error"] = true
      end
    end

    if user["mode"].to_i == -1 and (!user.key?("password") or user["password"] == "")
      errors["password"] = "Password is required"
      errors["error"] = true
    end


    if (user["mode"].to_i == -1) or (user["mode"].to_i != -1 and user.key?("role"))
      isGotRecord = Role.where(id: user["role"])
      if isGotRecord.length == 0
        errors["role"] = "Select Role"
        errors["error"] = true
      end
    end

    errors

  end

end