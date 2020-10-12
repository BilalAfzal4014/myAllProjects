require_relative '../../services/role_service/role_service'
require_relative '../../services/user_service/user_service'


class UserController::UserController < AuthenticatedController

  before_action :authenticate_user

  def getUser
    if session[:user_id]["role"] == "Employee" and session[:user_id]["id"].to_s != params[:id].to_s
      return redirect_to "/user/all/"
    end
    @roles = RoleServices.new().fetchRoles()
    @user = UserServices.new().fetchUser(params[:id])
    if session[:user_info] == nil
      session[:user_info] = {
          "id" => @user["id"],
          "name" => @user["name"],
          "email" => @user["email"],
          "role" => @user.role.role_id
      }
    end


    if session[:user_info]["role"] == 0
      session[:user_info]["role"] = @user.role.role_id
    end

    @mode = {
        "actionBtnText" => "update",
        "action" => "/user/update"
    }
    if session[:error] == nil
      session[:error] = {
          "name" => "",
          "email" => "",
          "password" => "",
          "role" => "",
          "error" => false
      }
    end
    render "user/create_update_user"
    session[:error] = nil
    session[:user_info] = nil
  end

  def createUserPage
    if !checkPermission("create-user")
      return
    end

    @roles = RoleServices.new().fetchRoles()
    if session[:user_info] == nil
      session[:user_info] = {
          "id" => -1,
          "name" => "",
          "email" => "",
          "role" => -1
      }
    end
    @mode = {
        "actionBtnText" => "create",
        "action" => "/user/create"
    }

    if session[:error] == nil
      session[:error] = {
          "name" => "",
          "email" => "",
          "password" => "",
          "role" => "",
          "error" => false
      }
    end
    render "user/create_update_user"
    session[:error] = nil
    session[:user_info] = nil
  end

  def create
    #request.body
    #request.raw_post
    #params

    if !checkPermission("create-user")
      return
    end

    errors = UserServices.new().userRequestValidation(params)
    session[:error] = errors

    if errors["error"]
      session[:user_info] = {
          "id" => params["mode"].to_i,
          "name" => params["name"],
          "email" => params["email"],
          "role" => params["role"].to_i
      }
      redirect_to "/user/create"
      return
    end

    userId = UserServices.new().createUser(params)
    RoleServices.new().assignRoleToUser(params["role"], userId)

    session[:user_info] = nil
    redirect_to "/user/create"
  end

  def update

    if session[:user_id]["role"] == "Employee" and session[:user_id]["id"].to_s != params["mode"].to_s
      return redirect_to "/user/all/"
    end

    errors = UserServices.new().userRequestValidation(params)
    session[:error] = errors

    if errors["error"]
      session[:user_info] = {
          "id" => params["mode"].to_i,
          "name" => params["name"],
          "email" => params["email"],
          "role" => !params.key?("role") ? 0 : params["role"].to_i
      }
      redirect_to "/user/update/" + params["mode"].to_s
      return
    end

    UserServices.new().updateUser(params)
    if session[:user_id]["role"] == "Employeer" and session[:user_id]["id"].to_s != params["mode"].to_s
      RoleServices.new().udpateRoleOfUser(params["role"], params["mode"])
    end
    session[:user_info] = nil
    redirect_to "/user/update/" + params["mode"].to_s
  end

  def all
    @users = UserServices.new().fetchUsers()
    render "user/list_users"
  end

  def logout
    ## puts session[:user_id]
    session[:user_id] = nil
    session[:error] = nil
    redirect_to '/auth/login'
  end
end
