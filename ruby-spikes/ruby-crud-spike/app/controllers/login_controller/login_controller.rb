require_relative '../../services/user_service/user_service'

class LoginController::LoginController < NonauthenticatedController
  before_action :authenticate_user

  def loginPage
    render "auth/login";
  end

  def authenticateUser
    userServiceObj = UserServices.new()
    user = userServiceObj.fetchUserByKey('email', params["email"])
    #if @user_hash == BCrypt::Engine.hash_secret(params[:password], @user.password_salt.to_s)
    if BCrypt::Password.new(user.password) == params["password"]
      session[:user_id] = userServiceObj.getUserRoleWithPermissions(user.id)
      redirect_to '/user/create'
    else
      redirect_to '/auth/login'
    end

  end

end
