class AuthenticatedController < ApplicationController
  protected

  def authenticate_user

    if !session[:user_id]
      redirect_to '/auth/login'
    end

  end

  def checkPermission(permission)
    if !session[:user_id]["permissions"].key?(permission)
      redirect_to '/user/all'
      return false
    end
    true
  end
end
