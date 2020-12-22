require 'bcrypt'
include BCrypt

User.create(  name: "Bilal Afzal",
              email: "bilal.afzal@zamee.com",
              password: BCrypt::Password.create("12345678")
              )
