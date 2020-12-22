class CreateUserRoleMappings < ActiveRecord::Migration[6.0]
  def change
    create_table :user_role_mappings do |t|
      t.bigint :user_id
      t.bigint :role_id

      t.timestamps
    end

    execute <<-SQL
      ALTER TABLE user_role_mappings
        ADD CONSTRAINT fk_user_user_role_mappings
        FOREIGN KEY (user_id)
        REFERENCES users(id)
    SQL

    execute <<-SQL
     ALTER TABLE user_role_mappings
        ADD CONSTRAINT fk_role_user_role_mappings
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
    SQL

  end
end
