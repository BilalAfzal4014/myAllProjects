Rails.application.routes.draw do

  scope '/auth' do
    get '/login', to: 'login_controller/login#loginPage'
    post '/login', to: 'login_controller/login#authenticateUser'
  end

  scope '/user' do
    get '/create', to: 'user_controller/user#createUserPage'
    get '/update/:id', to: 'user_controller/user#getUser'
    post '/create', to: 'user_controller/user#create'
    post '/update', to: 'user_controller/user#update'
    get '/all', to: 'user_controller/user#all'
    get '/logout', to: 'user_controller/user#logout'
  end

  scope '/order' do
    post '/create', to: 'order_controller/order#create'
    post '/update', to: 'order_controller/order#update'
    get '/all', to: 'order_controller/order#all'
    post '/approve-order', to: 'order_controller/order#approveOrder'
  end

end
