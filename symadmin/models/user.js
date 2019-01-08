module.exports = (sequelize, DataTypes) => {
  const Model = sequelize.define('user', {
    'firstname': {
      type: DataTypes.STRING,
    },
    'lastname': {
      type: DataTypes.STRING,
    },
    'email': {
      type: DataTypes.STRING,
    },
    'picture': {
      type: DataTypes.STRING,
    },
    'hash': {
      type: DataTypes.STRING,
    },
    'introduction': {
      type: DataTypes.STRING,
    },
    'description': {
      type: DataTypes.STRING,
    },
    'slug': {
      type: DataTypes.STRING,
    },
  }, {
    tableName: 'user',
    
    timestamps: false,
    
  });

  Model.associate = (models) => {
  };

  return Model;
};

