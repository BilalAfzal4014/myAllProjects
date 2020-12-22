class CreateRolePermissionMappings < ActiveRecord::Migration[6.0]
  def change
    create_table :role_permission_mappings do |t|
      t.bigint :role_id
      t.bigint :permission_id

      t.timestamps
    end

    execute <<-SQL
      ALTER TABLE role_permission_mappings
        ADD CONSTRAINT fk_role_role_permission_mappings
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
    SQL

    execute <<-SQL
      ALTER TABLE role_permission_mappings
      ADD CONSTRAINT fk_permission_role_permission_mappings
      FOREIGN KEY (permission_id)
      REFERENCES permissions(id)
    SQL
  end
end
