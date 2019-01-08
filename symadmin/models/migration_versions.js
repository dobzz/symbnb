module.exports = (sequelize, DataTypes) => {
  const Model = sequelize.define('migration_versions', {
    'version': {
      type: DataTypes.STRING,
      primaryKey: true 
    },
  }, {
    tableName: 'migration_versions',
    
    timestamps: false,
    
  });

  Model.associate = (models) => {
  };

  return Model;
};

