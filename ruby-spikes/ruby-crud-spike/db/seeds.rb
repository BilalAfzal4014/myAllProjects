# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rails db:seed command (or created alongside the database with db:setup).
#
# Examples:
#
#   movies = Movie.create([{ name: 'Star Wars' }, { name: 'Lord of the Rings' }])
#   Character.create(name: 'Luke', movie: movies.first)

require_relative 'seeder/user-seeder'
require_relative 'seeder/role-seeder'
require_relative 'seeder/permission-seeder'
require_relative 'seeder/user-role-mapping-seeder'

