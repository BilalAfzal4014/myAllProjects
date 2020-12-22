class NonauthenticatedController < ApplicationController

  protected

  def authenticate_user

    if session[:user_id]
      redirect_to '/user/create'

      ## redirect_to '/user/create', :notice => 'if you want to add a notice'
      ## if you want render 404 page
      ## render :file => File.join(Rails.root, 'public/404'), :formats => [:html], :status => 404, :layout => false
    end
  end


end
